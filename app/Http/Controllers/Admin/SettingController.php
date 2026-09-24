<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
}