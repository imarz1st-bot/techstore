<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use App\Models\StoreSetting;

class UserOrderController extends Controller
{
    public function create(Request $request)
    {
        $id = $request->query('product_id');

        abort_unless(
            is_string($id) && ctype_digit($id),
            404
        );

        $product = Product::query()
            ->where('status', 'aktif')
            ->findOrFail($id);

        if ($product->stok <= 0) {
            return redirect()->route('product.detail', [
                'id' => $product->id,
            ]);
        }

        $shippingFee = StoreSetting::current()->shippingFee();
        return view('order', compact('product', 'shippingFee'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'min:1'],
            'quantity' => ['required', 'integer', 'min:1', 'max:100000'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'recipient_phone' => ['required', 'string', 'max:25'],
            'shipping_address' => ['required', 'string', 'max:2000'],
            'shipping_fee' => [
                'required',
                'integer',
                'min:0',
                'max:10000000',
            ],
        ], [
            'quantity.required' => 'Jumlah pembelian wajib diisi.',
            'quantity.integer' => 'Jumlah pembelian harus berupa angka bulat.',
            'quantity.min' => 'Jumlah pembelian minimal 1.',
            'recipient_name.required' => 'Nama penerima wajib diisi.',
            'recipient_phone.required' => 'Nomor telepon wajib diisi.',
            'shipping_address.required' => 'Alamat pengiriman wajib diisi.',
            'payment_method.in' => 'Metode pembayaran tidak valid.',
        ]);

        $order = DB::transaction(function () use ($validated, $request) {
            // Kunci data produk selama pemeriksaan dan pengurangan stok.
            $product = Product::query()
                ->where('status', 'aktif')
                ->lockForUpdate()
                ->find($validated['product_id']);

            if (!$product) {
                throw ValidationException::withMessages([
                    'product_id' => 'Produk sudah tidak tersedia.',
                ]);
            }

            $quantity = (int) $validated['quantity'];

            if ($quantity > $product->stok) {
                throw ValidationException::withMessages([
                    'quantity' => 'Stok tidak cukup. Stok tersedia: '
                        . $product->stok . ' unit.',
                ]);
            }

            // Hitung dengan satuan sen agar nilai desimal tetap tepat.
            [$rupiah, $sen] = explode('.', (string) $product->harga);

            $hargaSen = ((int) $rupiah * 100) + (int) $sen;
            $subtotalSen = $hargaSen * $quantity;
            $shippingFee = StoreSetting::current()->shippingFee();
            if ((int) $validated['shipping_fee'] !== $shippingFee) {
                throw ValidationException::withMessages([
                    'shipping_fee' => 'Biaya pengiriman berubah. Refresh checkout '
                        . 'dan periksa total baru sebelum memesan kembali.',
                ]);
            }

            $totalSen = $subtotalSen + ($shippingFee * 100);

            $total = intdiv($totalSen, 100) . '.'
                . str_pad((string) ($totalSen % 100), 2, '0', STR_PAD_LEFT);

            $subtotal = intdiv($subtotalSen, 100) . '.'
                . str_pad((string) ($subtotalSen % 100), 2, '0', STR_PAD_LEFT);

            $order = Order::create([
                'user_id' => $request->user()->id,
                'nomor_pesanan' => 'TS-' . now()->format('Ymd') . '-'
                    . strtoupper((string) Str::ulid()),
                'nama_pelanggan' => $validated['recipient_name'],
                'email' => $request->user()->email,
                'no_hp' => $validated['recipient_phone'],
                'alamat' => $validated['shipping_address'],
                'subtotal' => $subtotal,
                'ongkos_kirim' => $shippingFee,
                'total' => $total,
                'metode_pembayaran' => null,
                'status' => 'menunggu',
                'tanggal_pesanan' => now(),
                'status_pembayaran' => 'belum_dibayar',
            ]);

            $order->items()->create([
                'product_id' => $product->id,
                'nama_produk' => $product->nama,
                'gambar' => $product->gambar,
                'harga' => $product->harga,
                'jumlah' => $quantity,
                'subtotal' => $subtotal,
            ]);

            $product->decrement('stok', $quantity);

            return $order;
        });

        return redirect()
    ->route('invoice', ['order' => $order->id])
    ->with('success', 'Pesanan berhasil dibuat.');
            
    }
    public function invoice(Request $request, int $order)
    {
        $order = Order::query()
            ->where('user_id', $request->user()->id)
            ->with('items')
            ->findOrFail($order);

        return view('invoice', compact('order'));
    }
    public function index(Request $request)
    {
        $orders = Order::query()
            ->where('user_id', $request->user()->id)
            ->with('items')
            ->orderByDesc('tanggal_pesanan')
            ->orderByDesc('id')
            ->paginate(10);

        return view('orders-user', compact('orders'));
    }
    public function cancel(Request $request, int $order)
    {
        DB::transaction(function () use ($request, $order) {
            $lockedOrder = Order::query()
                ->where('user_id', $request->user()->id)
                ->lockForUpdate()
                ->findOrFail($order);

            // Pembatalan berulang tidak mengembalikan stok lagi.
            if ($lockedOrder->status === 'dibatalkan') {
                return;
            }

            if (
                $lockedOrder->status !== 'menunggu'
                || $lockedOrder->status_pembayaran !== 'belum_dibayar'
            ) {
                throw ValidationException::withMessages([
                    'cancel' => 'Pesanan hanya bisa dibatalkan jika masih '
                        . 'menunggu dan belum memulai pembayaran.',
                ]);
            }

            $items = $lockedOrder->items()
                ->orderBy('product_id')
                ->get();

            foreach ($items as $item) {
                if ($item->product_id === null) {
                    continue;
                }

                $product = Product::query()
                    ->lockForUpdate()
                    ->find($item->product_id);

                if ($product) {
                    $product->increment('stok', $item->jumlah);
                }
            }

            $lockedOrder->update([
                'status' => 'dibatalkan',
            ]);
        });

        return redirect()
            ->route('orders.index')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }
    private function configureMidtrans(): void
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = (bool) config('midtrans.is_sanitized');
        Config::$is3ds = (bool) config('midtrans.is_3ds');
    }
    public function pay(Request $request, int $order)
    {
        $this->configureMidtrans();

        if (
            !filled(config('midtrans.server_key'))
            || !filled(config('midtrans.client_key'))
        ) {
            return response()->json([
                'message' => 'Konfigurasi Midtrans belum lengkap.',
            ], 503);
        }

        $payment = DB::transaction(function () use ($request, $order) {
            $lockedOrder = Order::query()
                ->where('user_id', $request->user()->id)
                ->lockForUpdate()
                ->findOrFail($order);

            if (
                $lockedOrder->status !== 'menunggu'
                || !in_array(
                    $lockedOrder->status_pembayaran,
                    ['belum_dibayar', 'pending'],
                    true
                )
            ) {
                throw ValidationException::withMessages([
                    'payment' => 'Pesanan ini tidak bisa dibayar.',
                ]);
            }

            // Gunakan token yang sudah tersimpan.
            if ($lockedOrder->snap_token) {
                return [
                    'token' => $lockedOrder->snap_token,
                    'order' => $lockedOrder,
                ];
            }

            // Jangan membuat transaksi baru jika permintaan sebelumnya
            // masih diproses atau hasilnya belum diketahui.
            if ($lockedOrder->midtrans_order_id) {
                throw ValidationException::withMessages([
                    'payment' => 'Transaksi sedang diproses atau perlu '
                        . 'diperiksa. Jangan membuat pembayaran baru.',
                ]);
            }

            $total = (string) $lockedOrder->total;
            [$rupiah, $sen] = explode('.', $total);

            if ((int) $sen !== 0 || (int) $rupiah <= 0) {
                throw ValidationException::withMessages([
                    'payment' => 'Total pembayaran harus berupa rupiah '
                        . 'bulat dan lebih dari nol.',
                ]);
            }

            $lockedOrder->update([
                'midtrans_order_id' => $lockedOrder->nomor_pesanan,
                'status_pembayaran' => 'pending',
            ]);

            return [
                'token' => null,
                'order' => $lockedOrder,
            ];
        });

        if ($payment['token']) {
            return response()->json([
                'token' => $payment['token'],
            ]);
        }

        $paymentOrder = $payment['order'];

        try {
            $token = Snap::getSnapToken([
                'transaction_details' => [
                    'order_id' => $paymentOrder->midtrans_order_id,
                    'gross_amount' => (int) $paymentOrder->total,
                ],
                'customer_details' => [
                    'first_name' => $paymentOrder->nama_pelanggan,
                    'email' => $paymentOrder->email,
                    'phone' => $paymentOrder->no_hp,
                ],
            ]);

            $paymentOrder->update([
                'snap_token' => $token,
            ]);

            return response()->json([
                'token' => $token,
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Token pembayaran belum berhasil diperoleh. '
                    . 'Periksa konfigurasi dan transaksi di Dashboard '
                    . 'Midtrans sebelum mencoba ulang.',
            ], 502);
        }
    }
    public function checkPayment(Request $request, int $order)
    {
        $paymentOrder = Order::query()
            ->where('user_id', $request->user()->id)
            ->findOrFail($order);

        if (!$paymentOrder->midtrans_order_id) {
            return back()->with(
                'payment_message',
                'Pembayaran belum dimulai.'
            );
        }

        $this->configureMidtrans();

        try {
            $response = Transaction::status(
                $paymentOrder->midtrans_order_id
            );

            $result = (array) $response;
        } catch (\Exception $exception) {
            return back()->with(
                'payment_message',
                'Status belum dapat diperiksa. Pastikan sudah memilih '
                . 'metode pembayaran di popup Midtrans, lalu coba kembali.'
            );
        }

        $message = DB::transaction(function () use (
            $paymentOrder,
            $result,
            $request
        ) {
            $lockedOrder = Order::query()
                ->where('user_id', $request->user()->id)
                ->lockForUpdate()
                ->findOrFail($paymentOrder->id);

            // Cocokkan identitas transaksi dan nominal.
            if (
                ($result['order_id'] ?? '') !== $lockedOrder->midtrans_order_id
                || !isset($result['gross_amount'])
                || number_format((float) $result['gross_amount'], 2, '.', '')
                    !== number_format((float) $lockedOrder->total, 2, '.', '')
            ) {
                throw ValidationException::withMessages([
                    'payment' => 'Data transaksi tidak cocok dengan pesanan.',
                ]);
            }

            // Pemeriksaan berulang tidak menurunkan status lunas.
            if ($lockedOrder->status_pembayaran === 'paid') {
                return 'Pembayaran sudah terverifikasi lunas.';
            }

            $transactionStatus = $result['transaction_status'] ?? '';
            $fraudStatus = $result['fraud_status'] ?? null;

            $isPaid = (
                $transactionStatus === 'settlement'
                && ($fraudStatus === null || $fraudStatus === 'accept')
            ) || (
                $transactionStatus === 'capture'
                && $fraudStatus === 'accept'
            );

            if ($isPaid) {
                if ($lockedOrder->status === 'dibatalkan') {
                    throw ValidationException::withMessages([
                        'payment' => 'Pembayaran diterima untuk pesanan '
                            . 'yang dibatalkan. Hubungi admin untuk pemeriksaan.',
                    ]);
                }

                $changes = [
                    'status_pembayaran' => 'paid',
                    'paid_at' => now(),
                ];

                // Pesanan yang sudah dibayar siap diproses admin.
                if ($lockedOrder->status === 'menunggu') {
                    $changes['status'] = 'diproses';
                }

                if (!empty($result['payment_type'])) {
                    $changes['metode_pembayaran'] = $result['payment_type'];
                }

                $lockedOrder->update($changes);

                return 'Pembayaran berhasil diverifikasi. Pesanan siap diproses.';
            }

            // Pembatalan atau kedaluwarsa yang dikonfirmasi Midtrans.
            if (in_array($transactionStatus, ['cancel', 'expire'], true)) {
                if ($lockedOrder->status === 'menunggu') {
                    $items = $lockedOrder->items()
                        ->orderBy('product_id')
                        ->get();

                    foreach ($items as $item) {
                        if ($item->product_id === null) {
                            continue;
                        }

                        $product = Product::query()
                            ->lockForUpdate()
                            ->find($item->product_id);

                        if ($product) {
                            $product->increment('stok', $item->jumlah);
                        }
                    }

                    $lockedOrder->update([
                        'status' => 'dibatalkan',
                        'status_pembayaran' => $transactionStatus === 'expire'
                            ? 'expired'
                            : 'cancelled',
                    ]);
                }

                return 'Transaksi dibatalkan atau kedaluwarsa. '
                    . 'Periksa status pesanan di invoice.';
            }

            if ($transactionStatus === 'deny') {
                return 'Pembayaran ditolak oleh Midtrans. '
                    . 'Pesanan belum dinyatakan lunas; periksa transaksi '
                    . 'di Dashboard Midtrans.';
            }

            return 'Pembayaran belum terverifikasi berhasil. '
                . 'Status Midtrans: ' . $transactionStatus . '.';
        });

        return redirect()
            ->route('invoice', ['order' => $paymentOrder->id])
            ->with('payment_message', $message);
    }
    
}