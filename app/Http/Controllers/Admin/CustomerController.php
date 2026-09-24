<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $customers = User::query()
            ->where('role', 'user')
            ->when($search, function ($query, $search) {
                $query->where(function ($subquery) use ($search) {
                    $subquery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('no_hp', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.customers.index', compact(
            'customers',
            'search',
            'status'
        ));
    }

    public function toggleStatus(User $customer)
    {
        abort_if($customer->role !== 'user', 404);

        $customer->update([
            'status' => $customer->status === 'aktif'
                ? 'nonaktif'
                : 'aktif',
        ]);

        $pesan = $customer->status === 'aktif'
            ? 'Pelanggan berhasil diaktifkan.'
            : 'Pelanggan berhasil dinonaktifkan.';

        return redirect()
            ->route('admin.customers.index')
            ->with('success', $pesan);
    }

    public function destroy(User $customer)
    {
        abort_if($customer->role !== 'user', 404);

        /*
         * Pelanggan yang mempunyai riwayat pesanan
         * tidak dihapus agar laporan tetap tersimpan.
         */
        if ($customer->orders()->exists()) {
            return redirect()
                ->route('admin.customers.index')
                ->with(
                    'error',
                    'Pelanggan tidak dapat dihapus karena mempunyai riwayat pesanan. Nonaktifkan akun pelanggan sebagai gantinya.'
                );
        }

        $customer->delete();

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }
}