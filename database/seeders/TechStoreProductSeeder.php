<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class TechStoreProductSeeder extends Seeder
{
    public function run(): void
    {
        // Sesuaikan kategori lama tanpa mengubah data produk lainnya.
        $categoryMap = [
            'Laptop' => 'laptop',
            'Gaming' => 'laptop',
            'MacBook' => 'laptop',
            'Mouse' => 'mouse',
            'Charger' => 'charger',
            'Monitor' => 'monitor',
            'Keyboard' => 'aksesoris',
            'Headset' => 'aksesoris',
            'Aksesoris' => 'aksesoris',
        ];

        foreach ($categoryMap as $old => $new) {
            Product::where('kategori', $old)
                ->update(['kategori' => $new]);
        }

        $products = [
            // LAPTOP
            [
                'nama' => 'TechStore StudentBook 14',
                'kategori' => 'laptop',
                'harga' => 5500000,
                'stok' => 12,
                'deskripsi' => "Laptop untuk kuliah dan pekerjaan sehari-hari.\n"
                    . "Prosesor: Intel Core i3\n"
                    . "RAM: 8 GB\n"
                    . "Penyimpanan: SSD 256 GB\n"
                    . "Layar: 14 inci Full HD\n"
                    . "Koneksi: Wi-Fi dan Bluetooth\n"
                    . "Kelengkapan: Laptop dan adaptor daya",
            ],
            [
                'nama' => 'TechStore OfficeBook 15',
                'kategori' => 'laptop',
                'harga' => 7500000,
                'stok' => 10,
                'deskripsi' => "Laptop untuk administrasi, presentasi, dan multitasking.\n"
                    . "Prosesor: Intel Core i5\n"
                    . "RAM: 16 GB\n"
                    . "Penyimpanan: SSD 512 GB\n"
                    . "Layar: 15,6 inci Full HD\n"
                    . "Koneksi: Wi-Fi dan Bluetooth\n"
                    . "Kelengkapan: Laptop dan adaptor daya",
            ],
            [
                'nama' => 'TechStore CreatorBook 14',
                'kategori' => 'laptop',
                'harga' => 9500000,
                'stok' => 8,
                'deskripsi' => "Laptop untuk pemrograman dan pekerjaan kreatif.\n"
                    . "Prosesor: AMD Ryzen 7\n"
                    . "RAM: 16 GB\n"
                    . "Penyimpanan: SSD 1 TB\n"
                    . "Layar: 14 inci IPS Full HD\n"
                    . "Koneksi: Wi-Fi dan Bluetooth\n"
                    . "Kelengkapan: Laptop dan adaptor daya",
            ],
            [
                'nama' => 'TechStore GameBook 15',
                'kategori' => 'laptop',
                'harga' => 12500000,
                'stok' => 6,
                'deskripsi' => "Laptop untuk gaming dan pengolahan grafis.\n"
                    . "Prosesor: Intel Core i7\n"
                    . "RAM: 16 GB\n"
                    . "Penyimpanan: SSD 512 GB\n"
                    . "Grafis: NVIDIA GeForce RTX 3050\n"
                    . "Layar: 15,6 inci Full HD 144 Hz\n"
                    . "Kelengkapan: Laptop dan adaptor daya",
            ],

            // MOUSE
            [
                'nama' => 'TechStore Mouse Wired M100',
                'kategori' => 'mouse',
                'harga' => 75000,
                'stok' => 40,
                'deskripsi' => "Mouse kabel untuk penggunaan sehari-hari.\n"
                    . "Koneksi: USB-A\n"
                    . "Sensor: Optik 1200 DPI\n"
                    . "Tombol: 3 tombol\n"
                    . "Panjang kabel: 1,5 meter\n"
                    . "Warna: Hitam\n"
                    . "Kelengkapan: Mouse dan panduan",
            ],
            [
                'nama' => 'TechStore Mouse Wireless M200',
                'kategori' => 'mouse',
                'harga' => 125000,
                'stok' => 35,
                'deskripsi' => "Mouse wireless dengan receiver USB.\n"
                    . "Koneksi: Wireless 2,4 GHz\n"
                    . "Sensor: Optik 1600 DPI\n"
                    . "Tombol: 3 tombol\n"
                    . "Daya: 1 baterai AA, tidak termasuk\n"
                    . "Warna: Abu-abu\n"
                    . "Kelengkapan: Mouse dan receiver USB",
            ],
            [
                'nama' => 'TechStore Mouse Bluetooth M300',
                'kategori' => 'mouse',
                'harga' => 185000,
                'stok' => 25,
                'deskripsi' => "Mouse Bluetooth untuk perangkat yang mendukung Bluetooth.\n"
                    . "Koneksi: Bluetooth\n"
                    . "Sensor: Optik hingga 2400 DPI\n"
                    . "Tombol: 4 tombol\n"
                    . "Daya: Baterai isi ulang\n"
                    . "Pengisian: USB-C\n"
                    . "Kelengkapan: Mouse dan kabel pengisian",
            ],
            [
                'nama' => 'TechStore Mouse Gaming M400',
                'kategori' => 'mouse',
                'harga' => 275000,
                'stok' => 20,
                'deskripsi' => "Mouse gaming berkabel dengan pencahayaan RGB.\n"
                    . "Koneksi: USB-A\n"
                    . "Sensor: Optik hingga 6400 DPI\n"
                    . "Tombol: 6 tombol\n"
                    . "Panjang kabel: 1,8 meter\n"
                    . "Warna: Hitam\n"
                    . "Kelengkapan: Mouse dan panduan",
            ],

            // CHARGER
            [
                'nama' => 'TechStore Charger USB-C PD 45W',
                'kategori' => 'charger',
                'harga' => 225000,
                'stok' => 25,
                'deskripsi' => "Adaptor USB-C Power Delivery dengan daya maksimal 45W.\n"
                    . "Input: AC 100–240V\n"
                    . "Output: USB-C PD hingga 45W\n"
                    . "Port: 1 USB-C\n"
                    . "Kelengkapan: Adaptor, tanpa kabel\n"
                    . "Kompatibilitas: Perangkat USB-C PD dengan profil daya yang sesuai.\n"
                    . "Periksa kebutuhan daya perangkat sebelum membeli.",
            ],
            [
                'nama' => 'TechStore Charger USB-C PD 65W',
                'kategori' => 'charger',
                'harga' => 350000,
                'stok' => 20,
                'deskripsi' => "Adaptor untuk perangkat yang mendukung pengisian USB-C PD.\n"
                    . "Input: AC 100–240V\n"
                    . "Output: USB-C PD hingga 65W\n"
                    . "Port: 1 USB-C\n"
                    . "Kelengkapan: Adaptor dan kabel USB-C berdaya sesuai\n"
                    . "Kompatibilitas: Perangkat USB-C PD dengan profil daya yang sesuai.\n"
                    . "Tidak untuk laptop yang hanya menerima konektor barrel.",
            ],
            [
                'nama' => 'TechStore Charger USB-C PD 100W',
                'kategori' => 'charger',
                'harga' => 550000,
                'stok' => 15,
                'deskripsi' => "Adaptor USB-C PD dengan daya maksimal 100W.\n"
                    . "Input: AC 100–240V\n"
                    . "Output: USB-C PD hingga 100W\n"
                    . "Port: 1 USB-C\n"
                    . "Kelengkapan: Adaptor dan kabel USB-C 100W dengan e-marker\n"
                    . "Kompatibilitas: Perangkat USB-C PD dengan profil daya yang sesuai.\n"
                    . "Daya pengisian mengikuti kemampuan perangkat.",
            ],
            [
                'nama' => 'TechStore Charger Barrel 65W 19V',
                'kategori' => 'charger',
                'harga' => 250000,
                'stok' => 18,
                'deskripsi' => "Adaptor laptop dengan konektor barrel.\n"
                    . "Input: AC 100–240V\n"
                    . "Output: DC 19V, 3,42A\n"
                    . "Daya: Sekitar 65W\n"
                    . "Konektor: 5,5 × 2,5 mm, positif di tengah\n"
                    . "Kelengkapan: Adaptor dan kabel listrik\n"
                    . "Hanya untuk perangkat dengan tegangan, polaritas, "
                    . "konektor, dan kebutuhan arus yang sesuai.",
            ],

            // MONITOR
            [
                'nama' => 'TechStore Monitor Office 22',
                'kategori' => 'monitor',
                'harga' => 1250000,
                'stok' => 15,
                'deskripsi' => "Monitor untuk belajar dan pekerjaan kantor.\n"
                    . "Ukuran: 21,5 inci\n"
                    . "Resolusi: 1920 × 1080 Full HD\n"
                    . "Panel: IPS\n"
                    . "Refresh rate: 75 Hz\n"
                    . "Port: HDMI dan VGA\n"
                    . "Kelengkapan: Monitor, dudukan, kabel daya, dan HDMI",
            ],
            [
                'nama' => 'TechStore Monitor IPS 24',
                'kategori' => 'monitor',
                'harga' => 1750000,
                'stok' => 12,
                'deskripsi' => "Monitor IPS untuk produktivitas sehari-hari.\n"
                    . "Ukuran: 23,8 inci\n"
                    . "Resolusi: 1920 × 1080 Full HD\n"
                    . "Panel: IPS\n"
                    . "Refresh rate: 100 Hz\n"
                    . "Port: HDMI dan DisplayPort\n"
                    . "Kelengkapan: Monitor, dudukan, kabel daya, dan HDMI",
            ],
            [
                'nama' => 'TechStore Monitor Gaming 24',
                'kategori' => 'monitor',
                'harga' => 2500000,
                'stok' => 10,
                'deskripsi' => "Monitor untuk gaming dengan refresh rate tinggi.\n"
                    . "Ukuran: 24 inci\n"
                    . "Resolusi: 1920 × 1080 Full HD\n"
                    . "Panel: IPS\n"
                    . "Refresh rate: Hingga 165 Hz melalui DisplayPort\n"
                    . "Port: HDMI dan DisplayPort\n"
                    . "Kelengkapan: Monitor, dudukan, kabel daya, dan DisplayPort",
            ],
            [
                'nama' => 'TechStore Monitor QHD 27',
                'kategori' => 'monitor',
                'harga' => 3500000,
                'stok' => 8,
                'deskripsi' => "Monitor dengan area kerja luas untuk multitasking.\n"
                    . "Ukuran: 27 inci\n"
                    . "Resolusi: 2560 × 1440 QHD\n"
                    . "Panel: IPS\n"
                    . "Refresh rate: 75 Hz\n"
                    . "Port: HDMI dan DisplayPort\n"
                    . "Kelengkapan: Monitor, dudukan, kabel daya, dan DisplayPort",
            ],

            // AKSESORIS
            [
                'nama' => 'TechStore Keyboard Wireless K100',
                'kategori' => 'aksesoris',
                'harga' => 225000,
                'stok' => 30,
                'deskripsi' => "Keyboard wireless untuk mengetik dan pekerjaan kantor.\n"
                    . "Koneksi: Wireless 2,4 GHz melalui receiver USB\n"
                    . "Layout: Full-size dengan numpad\n"
                    . "Tipe tombol: Membrane\n"
                    . "Daya: 2 baterai AAA, tidak termasuk\n"
                    . "Kelengkapan: Keyboard dan receiver USB",
            ],
            [
                'nama' => 'TechStore Headset Office H100',
                'kategori' => 'aksesoris',
                'harga' => 185000,
                'stok' => 25,
                'deskripsi' => "Headset untuk kelas online dan rapat virtual.\n"
                    . "Koneksi: Jack audio 3,5 mm TRRS\n"
                    . "Mikrofon: Boom microphone\n"
                    . "Panjang kabel: 1,8 meter\n"
                    . "Kontrol: Volume pada kabel\n"
                    . "Kelengkapan: Headset dan panduan",
            ],
            [
                'nama' => 'TechStore USB-C Hub 5-in-1',
                'kategori' => 'aksesoris',
                'harga' => 325000,
                'stok' => 20,
                'deskripsi' => "Hub untuk menambah koneksi perangkat USB-C.\n"
                    . "Port: 2 USB-A, 1 HDMI, 1 pembaca SD, dan 1 USB-C PD\n"
                    . "HDMI: Hingga 4K 30 Hz\n"
                    . "Pengisian pass-through: Hingga 100W input\n"
                    . "Output HDMI memerlukan USB-C dengan DisplayPort Alt Mode.\n"
                    . "Kelengkapan: Hub dan panduan",
            ],
            [
                'nama' => 'TechStore Laptop Stand Aluminium',
                'kategori' => 'aksesoris',
                'harga' => 175000,
                'stok' => 30,
                'deskripsi' => "Dudukan laptop lipat untuk meja kerja.\n"
                    . "Material: Aluminium\n"
                    . "Ukuran perangkat: Laptop 11–15,6 inci\n"
                    . "Pengaturan: Beberapa tingkat kemiringan\n"
                    . "Bantalan: Silikon antiselip\n"
                    . "Kelengkapan: Dudukan dan kantong penyimpanan",
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['nama' => $product['nama']],
                array_merge($product, [
                    'gambar' => null,
                    'status' => 'aktif',
                ])
            );
        }
    }
}