<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        /*
         * Sementara menggunakan data desain.
         * Setelah tabel products dan orders dibuat,
         * data ini akan diambil langsung dari database.
         */
        $summary = [
            'products' => 128,
            'orders' => 64,
            'customers' => 312,
            'income' => 'Rp128,4 jt',
        ];

        $latestOrders = [
            [
                'number' => 'ORD-1024',
                'customer' => 'Ahmad',
                'status' => 'Selesai'
            ],
            [
                'number' => 'ORD-1023',
                'customer' => 'Rizky',
                'status' => 'Diproses'
            ],
            [
                'number' => 'ORD-1022',
                'customer' => 'Dinda',
                'status' => 'Dikirim'
            ],
            [
                'number' => 'ORD-1021',
                'customer' => 'Fajar',
                'status' => 'Selesai'
            ],
        ];

        $bestProducts = [
            [
                'name' => 'ASUS Vivobook 14',
                'sold' => 32,
                'income' => 'Rp18,5 jt',
                'percentage' => 80
            ],
            [
                'name' => 'Lenovo IdeaPad Slim 3',
                'sold' => 27,
                'income' => 'Rp15,2 jt',
                'percentage' => 68
            ],
        ];

        return view('admin.dashboard', compact(
            'summary',
            'latestOrders',
            'bestProducts'
        ));
    }
}