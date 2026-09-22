<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'nama' => 'ASUS TUF Gaming F15',
                'kategori' => 'Gaming',
                'harga' => 12499000,
                'stok' => 15,
                'status' => 'aktif',
                'deskripsi' => 'Laptop gaming ASUS TUF Gaming F15.',
            ],
            [
                'nama' => 'MacBook Air M2',
                'kategori' => 'MacBook',
                'harga' => 18999000,
                'stok' => 8,
                'status' => 'aktif',
                'deskripsi' => 'MacBook Air dengan prosesor Apple M2.',
            ],
            [
                'nama' => 'Lenovo IdeaPad 3',
                'kategori' => 'Laptop',
                'harga' => 9499000,
                'stok' => 20,
                'status' => 'aktif',
                'deskripsi' => 'Laptop untuk kebutuhan kuliah dan pekerjaan.',
            ],
            [
                'nama' => 'HP Pavilion 14',
                'kategori' => 'Laptop',
                'harga' => 10999000,
                'stok' => 12,
                'status' => 'aktif',
                'deskripsi' => 'Laptop HP Pavilion berukuran 14 inci.',
            ],
            [
                'nama' => 'Acer Nitro 5',
                'kategori' => 'Gaming',
                'harga' => 11999000,
                'stok' => 10,
                'status' => 'aktif',
                'deskripsi' => 'Laptop gaming Acer Nitro 5.',
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['nama' => $product['nama']],
                $product
            );
        }
    }
}