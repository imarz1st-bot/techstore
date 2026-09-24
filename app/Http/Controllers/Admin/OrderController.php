<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

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
                'in:menunggu,diproses,dikirim,selesai,dibatalkan'
            ],
        ], [
            'status.required' => 'Status pesanan wajib dipilih.',
            'status.in' => 'Status pesanan tidak valid.',
        ]);

        $order->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }
}