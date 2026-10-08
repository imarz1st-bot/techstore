<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $search = trim($request->input('search') ?? '');

        $categories = [
            'laptop' => 'Laptop',
            'mouse' => 'Mouse',
            'charger' => 'Charger',
            'monitor' => 'Monitor',
            'aksesoris' => 'Aksesoris',
        ];

        $productsByCategory = Product::query()
            ->where('status', 'aktif')
            ->whereIn('kategori', array_keys($categories))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('nama', 'like', "%{$search}%")
                        ->orWhere('kategori', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get()
            ->groupBy(fn ($product) => strtolower($product->kategori));

        return view('dashboard-user', compact(
            'categories',
            'productsByCategory',
            'search'
        ));
    }
}