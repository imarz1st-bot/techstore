<?php

namespace App\Http\Controllers;


use App\Models\Product;
use Illuminate\Http\Request;

class UserProductController extends Controller
{
    public function show(Request $request)
    {
        $id = $request->query('id');

        abort_unless(
            is_string($id) && ctype_digit($id),
            404
        );

        $product = Product::query()
            ->where('status', 'aktif')
            ->findOrFail($id);

        return view('product-detail', compact('product'));
    }
}