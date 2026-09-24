<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_akhir' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_mulai'
            ],
        ]);

        /*
         * Jika admin belum memilih tanggal,
         * gunakan tujuh hari terakhir.
         */
        $tanggalMulai = $request->filled('tanggal_mulai')
            ? Carbon::parse($request->tanggal_mulai)->startOfDay()
            : now()->subDays(6)->startOfDay();

        $tanggalAkhir = $request->filled('tanggal_akhir')
            ? Carbon::parse($request->tanggal_akhir)->endOfDay()
            : now()->endOfDay();

        $jumlahHari = $tanggalMulai->diffInDays($tanggalAkhir) + 1;

        /*
         * Periode sebelumnya dipakai untuk menghitung persentase.
         */
        $periodeSebelumnyaAkhir = $tanggalMulai
            ->copy()
            ->subDay()
            ->endOfDay();

        $periodeSebelumnyaMulai = $periodeSebelumnyaAkhir
            ->copy()
            ->subDays($jumlahHari - 1)
            ->startOfDay();

        $pesananSelesai = Order::query()
            ->where('status', 'selesai')
            ->whereBetween('tanggal_pesanan', [
                $tanggalMulai,
                $tanggalAkhir,
            ]);

        $pesananSebelumnya = Order::query()
            ->where('status', 'selesai')
            ->whereBetween('tanggal_pesanan', [
                $periodeSebelumnyaMulai,
                $periodeSebelumnyaAkhir,
            ]);

        $totalPenjualan = (clone $pesananSelesai)->sum('total');
        $jumlahTransaksi = (clone $pesananSelesai)->count();

        $totalPenjualanSebelumnya =
            (clone $pesananSebelumnya)->sum('total');

        $jumlahTransaksiSebelumnya =
            (clone $pesananSebelumnya)->count();

        $produkTerjual = OrderItem::query()
            ->whereHas('order', function ($query) use (
                $tanggalMulai,
                $tanggalAkhir
            ) {
                $query
                    ->where('status', 'selesai')
                    ->whereBetween('tanggal_pesanan', [
                        $tanggalMulai,
                        $tanggalAkhir,
                    ]);
            })
            ->sum('jumlah');

        $produkTerjualSebelumnya = OrderItem::query()
            ->whereHas('order', function ($query) use (
                $periodeSebelumnyaMulai,
                $periodeSebelumnyaAkhir
            ) {
                $query
                    ->where('status', 'selesai')
                    ->whereBetween('tanggal_pesanan', [
                        $periodeSebelumnyaMulai,
                        $periodeSebelumnyaAkhir,
                    ]);
            })
            ->sum('jumlah');

        $persentasePenjualan = $this->hitungPersentase(
            $totalPenjualan,
            $totalPenjualanSebelumnya
        );

        $persentaseTransaksi = $this->hitungPersentase(
            $jumlahTransaksi,
            $jumlahTransaksiSebelumnya
        );

        $persentaseProduk = $this->hitungPersentase(
            $produkTerjual,
            $produkTerjualSebelumnya
        );

        /*
         * Membuat data grafik berdasarkan setiap tanggal.
         */
        $grafik = [];

        $periodeTanggal = CarbonPeriod::create(
            $tanggalMulai->copy()->startOfDay(),
            $tanggalAkhir->copy()->startOfDay()
        );

        foreach ($periodeTanggal as $tanggal) {
            $totalHarian = Order::query()
                ->where('status', 'selesai')
                ->whereDate('tanggal_pesanan', $tanggal)
                ->sum('total');

            $grafik[] = [
                'tanggal' => $tanggal->translatedFormat('d M'),
                'total' => (float) $totalHarian,
            ];
        }

        $nilaiGrafikTertinggi = collect($grafik)->max('total') ?: 1;

        /*
         * Jumlah produk terjual berdasarkan kategori.
         */
        $kategoriQuery = DB::table('order_items')
            ->join(
                'orders',
                'orders.id',
                '=',
                'order_items.order_id'
            )
            ->leftJoin(
                'products',
                'products.id',
                '=',
                'order_items.product_id'
            )
            ->where('orders.status', 'selesai')
            ->whereBetween('orders.tanggal_pesanan', [
                $tanggalMulai,
                $tanggalAkhir,
            ])
            ->selectRaw(
                "COALESCE(products.kategori, 'Lainnya') AS kategori"
            )
            ->selectRaw('SUM(order_items.jumlah) AS jumlah')
            ->groupBy('kategori')
            ->orderByDesc('jumlah')
            ->get();

        $totalKategori = $kategoriQuery->sum('jumlah');

        $warnaKategori = [
            '#2d67eb',
            '#8354e9',
            '#1fc36a',
            '#f5a10d',
            '#ef4444',
        ];

        $posisiAwal = 0;
        $kategoriProduk = [];

        foreach ($kategoriQuery as $index => $kategori) {
            $persentase = $totalKategori > 0
                ? round(($kategori->jumlah / $totalKategori) * 100, 1)
                : 0;

            $kategoriProduk[] = [
                'nama' => $kategori->kategori,
                'jumlah' => (int) $kategori->jumlah,
                'persentase' => $persentase,
                'warna' => $warnaKategori[
                    $index % count($warnaKategori)
                ],
                'mulai' => $posisiAwal,
                'akhir' => $posisiAwal + $persentase,
            ];

            $posisiAwal += $persentase;
        }

        $donutGradient = empty($kategoriProduk)
            ? '#e5e9ef 0% 100%'
            : collect($kategoriProduk)
                ->map(function ($kategori) {
                    return "{$kategori['warna']} "
                        . "{$kategori['mulai']}% "
                        . "{$kategori['akhir']}%";
                })
                ->implode(', ');

        return view('admin.reports.index', compact(
            'tanggalMulai',
            'tanggalAkhir',
            'totalPenjualan',
            'jumlahTransaksi',
            'produkTerjual',
            'persentasePenjualan',
            'persentaseTransaksi',
            'persentaseProduk',
            'grafik',
            'nilaiGrafikTertinggi',
            'kategoriProduk',
            'donutGradient'
        ));
    }

    private function hitungPersentase(
        float|int $nilaiSekarang,
        float|int $nilaiSebelumnya
    ): float {
        if ($nilaiSebelumnya == 0) {
            return $nilaiSekarang > 0 ? 100 : 0;
        }

        return round(
            (($nilaiSekarang - $nilaiSebelumnya)
                / $nilaiSebelumnya) * 100,
            1
        );
    }
}