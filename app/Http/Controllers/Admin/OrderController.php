<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalAkhir = $request->input('tanggal_akhir');

        $orders = Order::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($subquery) use ($search) {
                    $subquery
                        ->where('nomor_pesanan', 'like', "%{$search}%")
                        ->orWhere('nama_pelanggan', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($tanggalMulai, function ($query, $tanggalMulai) {
                $query->whereDate(
                    'tanggal_pesanan',
                    '>=',
                    $tanggalMulai
                );
            })
            ->when($tanggalAkhir, function ($query, $tanggalAkhir) {
                $query->whereDate(
                    'tanggal_pesanan',
                    '<=',
                    $tanggalAkhir
                );
            })
            ->latest('tanggal_pesanan')
            ->paginate(10)
            ->withQueryString();

        return view('admin.orders.index', compact(
            'orders',
            'search',
            'status',
            'tanggalMulai',
            'tanggalAkhir'
        ));
    }
    public function show(Order $order)
    {
        $order->load([
            'user',
            'items.product'
        ]);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:menunggu,diproses,dikirim,selesai,dibatalkan',
            ],
        ], [
            'status.required' => 'Status pesanan wajib dipilih.',
            'status.in' => 'Status pesanan tidak valid.',
        ]);

        DB::transaction(function () use ($validated, $order) {
            // Ambil status terbaru dan kunci pesanan.
            $lockedOrder = Order::query()
                ->lockForUpdate()
                ->findOrFail($order->id);

            $statusLama = $lockedOrder->status;
            $statusBaru = $validated['status'];

            // Status yang sama tidak mengubah stok.
            if ($statusLama === $statusBaru) {
                return;
            }

            $transisi = [
                'menunggu' => ['diproses', 'dibatalkan'],
                'diproses' => ['dikirim', 'dibatalkan'],
                'dikirim' => ['selesai'],
                'selesai' => [],
                'dibatalkan' => [],
            ];

            if (!in_array($statusBaru, $transisi[$statusLama] ?? [], true)) {
                throw ValidationException::withMessages([
                    'status' => "Status {$statusLama} tidak bisa diubah menjadi {$statusBaru}.",
                ]);
            }

            if ($statusBaru === 'dibatalkan') {
                if ($lockedOrder->status_pembayaran !== 'belum_dibayar') {
                    throw ValidationException::withMessages([
                        'status' => 'Pembayaran sudah dimulai. Pembatalan harus '
                            . 'ditangani melalui proses payment gateway.',
                    ]);
                }
                $items = $lockedOrder->items()
                    ->orderBy('product_id')
                    ->get();

                foreach ($items as $item) {
                    // Produk yang sudah dihapus tidak memiliki stok untuk dikembalikan.
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
            }

            $lockedOrder->update([
                'status' => $statusBaru,
            ]);
        });

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }
}