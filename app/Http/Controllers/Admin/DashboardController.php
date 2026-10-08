<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = CarbonImmutable::now('Asia/Jakarta')->startOfDay();

        // Timestamp database mengikuti timezone aplikasi.
        $databaseTimezone = config('app.timezone', 'UTC');

        $monthStart = $today->startOfMonth()
            ->setTimezone($databaseTimezone);

        $nextMonthStart = $today->startOfMonth()
            ->addMonth()
            ->setTimezone($databaseTimezone);

        $paidOrders = Order::query()
            ->where('status_pembayaran', 'paid')
            ->where('status', '!=', 'dibatalkan');

        $income = (clone $paidOrders)->sum('total');

        $summary = [
            'products' => Product::count(),
            'orders' => Order::count(),

            'customers' => User::where('role', 'user')->count(),

            'income' => 'Rp ' . number_format($income, 0, ',', '.'),

            'products_month' => Product::query()
                ->where('created_at', '>=', $monthStart)
                ->where('created_at', '<', $nextMonthStart)
                ->count(),

            'waiting_orders' => Order::where('status', 'menunggu')
                ->count(),

            'customers_month' => User::query()
                ->where('role', 'user')
                ->where('created_at', '>=', $monthStart)
                ->where('created_at', '<', $nextMonthStart)
                ->count(),
        ];

        $latestOrders = Order::query()
            ->orderByDesc('tanggal_pesanan')
            ->orderByDesc('id')
            ->limit(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'number' => $order->nomor_pesanan,
                    'customer' => $order->nama_pelanggan,
                    'status' => ucfirst($order->status),
                ];
            });

        // Jumlah pembayaran berhasil pada setiap tanggal.
        $salesDays = collect();

        for ($i = 6; $i >= 0; $i--) {
            $day = $today->subDays($i);

            $count = (clone $paidOrders)
                ->where(
                    'paid_at',
                    '>=',
                    $day->setTimezone($databaseTimezone)
                )
                ->where(
                    'paid_at',
                    '<',
                    $day->addDay()->setTimezone($databaseTimezone)
                )
                ->count();

            $salesDays->push([
                'label' => $day->format('d/m'),
                'count' => $count,
            ]);
        }

        $maxSales = max(1, (int) $salesDays->max('count'));

        $salesPoints = $salesDays->map(function ($day, $index) use ($maxSales) {
            return [
                'x' => 40 + ($index * 104),
                'y' => round(185 - ($day['count'] / $maxSales * 150), 2),
                'label' => $day['label'],
                'count' => $day['count'],
            ];
        });

        $salesPolyline = $salesPoints
            ->map(fn ($point) => $point['x'] . ',' . $point['y'])
            ->implode(' ');

        // Gunakan nama produk saat ini jika produk masih ada.
        // Produk yang dihapus tetap memiliki nama dari riwayat pembelian.
        $productName = 'COALESCE(products.nama, order_items.nama_produk)';

        $bestProducts = OrderItem::query()
            ->leftJoin(
                'products',
                'order_items.product_id',
                '=',
                'products.id'
            )
            ->whereHas('order', function ($query) {
                $query->where('status_pembayaran', 'paid')
                    ->where('status', '!=', 'dibatalkan');
            })
            ->select('order_items.product_id')
            ->selectRaw($productName . ' AS name')
            ->selectRaw('SUM(order_items.jumlah) AS sold')
            ->selectRaw('SUM(order_items.subtotal) AS income')
            ->groupBy('order_items.product_id', DB::raw($productName))
            ->orderByDesc('sold')
            ->orderBy('name')
            ->limit(5)
            ->get();

        $maxSold = max(1, (int) $bestProducts->max('sold'));

        $bestProducts = $bestProducts->map(function ($product) use ($maxSold) {
            return [
                'name' => $product->name,
                'sold' => (int) $product->sold,
                'income' => 'Rp '
                    . number_format($product->income, 0, ',', '.'),
                'percentage' => round($product->sold / $maxSold * 100),
            ];
        });

        return view('admin.dashboard', compact(
            'summary',
            'latestOrders',
            'bestProducts',
            'salesDays',
            'salesPoints',
            'salesPolyline'
        ));
    }
}