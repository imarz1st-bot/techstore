<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function index()
    {
        /*
         * Pengaturan awal otomatis dibuat jika tabel masih kosong.
         */
        $setting = StoreSetting::firstOrCreate(
            ['id' => 1],
            [
                'nama_toko' => 'LaptopStore',
                'email' => 'info@laptopstore.com',
                'no_hp' => '0812 3456 7890',
                'alamat' => 'Jl. Teknologi No. 1, Depok',
            ]
        );

        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = StoreSetting::firstOrCreate(
            ['id' => 1],
            [
                'nama_toko' => 'LaptopStore',
                'email' => 'info@laptopstore.com',
            ]
        );

        $validated = $request->validate([
            'nama_toko' => [
                'required',
                'string',
                'max:255'
            ],
            'email' => [
                'required',
                'email',
                'max:255'
            ],
            'no_hp' => [
                'nullable',
                'string',
                'max:25'
            ],
            'alamat' => [
                'nullable',
                'string',
                'max:1000'
            ],
            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],
        ], [
            'nama_toko.required' => 'Nama toko wajib diisi.',
            'email.required' => 'Email toko wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'logo.image' => 'File logo harus berupa gambar.',
            'logo.mimes' => 'Logo harus berformat JPG, JPEG, PNG, atau WEBP.',
            'logo.max' => 'Ukuran logo maksimal 2 MB.',
        ]);

        $data = [
            'nama_toko' => $validated['nama_toko'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
        ];

        if ($request->hasFile('logo')) {
            $logoBaru = $request
                ->file('logo')
                ->store('store', 'public');

            if ($setting->logo) {
                Storage::disk('public')->delete($setting->logo);
            }

            $data['logo'] = $logoBaru;
        }

        $setting->update($data);

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Pengaturan toko berhasil disimpan.');
    }
    public function updateAccount(Request $request)
    {
        $admin = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'admin_email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($admin->id),
            ],

            'current_password' => [
                'required',
                'current_password',
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'name.required' => 'Nama admin wajib diisi.',
            'admin_email.required' => 'Email admin wajib diisi.',
            'admin_email.email' => 'Format email tidak valid.',
            'admin_email.unique' => 'Email sudah digunakan akun lain.',
            'current_password.required' => 'Masukkan password saat ini.',
            'current_password.current_password' => 'Password saat ini salah.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $admin->name = $validated['name'];
        $admin->email = $validated['admin_email'];

        if (!empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        $request->session()->regenerate();

        return redirect()
            ->route('admin.settings.index', ['tab' => 'account'])
            ->with('success', 'Akun admin berhasil diperbarui.');
    }
    public function updateShipping(Request $request)
    {
        $validated = $request->validate([
            'shipping_type' => ['required', 'in:free,flat'],

            'shipping_cost' => [
                'required_if:shipping_type,flat',
                'nullable',
                'integer',
                'min:0',
                'max:10000000',
            ],
        ], [
            'shipping_type.required' => 'Pilih jenis ongkir.',
            'shipping_type.in' => 'Jenis ongkir tidak valid.',
            'shipping_cost.required_if' => 'Isi biaya pengiriman tetap.',
            'shipping_cost.integer' => 'Biaya pengiriman harus angka bulat.',
            'shipping_cost.min' => 'Biaya pengiriman tidak boleh negatif.',
        ]);

        $setting = StoreSetting::current();

        $setting->update([
            'shipping_type' => $validated['shipping_type'],

            'shipping_cost' => $validated['shipping_type'] === 'flat'
                ? (int) $validated['shipping_cost']
                : 0,
        ]);

        return redirect()
            ->route('admin.settings.index', ['tab' => 'shipping'])
            ->with('success', 'Pengaturan ongkos kirim berhasil disimpan.');
    }
}