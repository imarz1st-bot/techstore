<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laporan Penjualan - LaptopStore</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-products.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-reports.css') }}">
</head>
<body>

<aside class="sidebar no-print">
    <div>
        <div class="brand product-brand">
            <div class="brand-logo">
                <i class="bi bi-laptop"></i>
            </div>

            <div>
                <h2>LaptopStore</h2>
                <span>Admin Panel</span>
            </div>
        </div>

        <nav class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="menu">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.products.index') }}" class="menu">
                <i class="bi bi-box-seam"></i>
                <span>Produk</span>
            </a>

            <a href="{{ route('admin.orders.index') }}" class="menu">
                <i class="bi bi-cart3"></i>
                <span>Pesanan</span>
            </a>

            <a href="{{ route('admin.customers.index') }}" class="menu">
                <i class="bi bi-people"></i>
                <span>Pelanggan</span>
            </a>

            <a href="{{ route('admin.reports.index') }}"
               class="menu active">
                <i class="bi bi-bar-chart-fill"></i>
                <span>Laporan</span>
            </a>

           <a href="{{ route('admin.settings.index') }}" class="menu">
                <i class="bi bi-gear"></i>
                <span>Pengaturan</span>
            </a>
        </nav>
    </div>

    <form action="{{ route('logout') }}"
          method="POST"
          class="logout-form">
        @csrf

        <button type="submit">
            <i class="bi bi-chevron-left"></i>
            <span>Keluar</span>
        </button>
    </form>
</aside>

<main class="main-content">
    <header class="topbar product-topbar no-print">
        <div class="top-search">
            <i class="bi bi-search"></i>
            <span>Cari laporan...</span>
        </div>

        <div class="admin-profile">
            <div class="avatar">
                <i class="bi bi-person"></i>
            </div>

            <div class="admin-information">
                <strong>{{ auth()->user()->name }}</strong>
                <small>Super Admin</small>
            </div>
        </div>
    </header>

    <section class="reports-content">
        <div class="report-heading">
            <div>
                <h1>Laporan Penjualan</h1>
                <p>Lihat ringkasan data dan cetak laporan</p>
            </div>

            <div class="report-actions no-print">
                <form action="{{ route('admin.reports.index') }}"
                      method="GET"
                      class="date-range-form">

                    <i class="bi bi-calendar3"></i>

                    <input
                        type="date"
                        name="tanggal_mulai"
                        value="{{ $tanggalMulai->format('Y-m-d') }}"
                    >

                    <span>—</span>

                    <input
                        type="date"
                        name="tanggal_akhir"
                        value="{{ $tanggalAkhir->format('Y-m-d') }}"
                    >

                    <button type="submit">Terapkan</button>
                </form>

                <button type="button"
                        class="export-button"
                        onclick="window.print()">
                    <i class="bi bi-download"></i>
                    Cetak Laporan
                </button>
            </div>
        </div>

        @if($errors->any())
            <div class="report-error no-print">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="report-period print-only">
            Periode:
            {{ $tanggalMulai->translatedFormat('d F Y') }}
            sampai
            {{ $tanggalAkhir->translatedFormat('d F Y') }}
        </div>

        <div class="summary-report-grid">
            <article class="report-summary-card">
                <span>Total Penjualan</span>

                <strong>
                    Rp {{ number_format(
                        $totalPenjualan,
                        0,
                        ',',
                        '.'
                    ) }}
                </strong>

                <small class="{{ $persentasePenjualan >= 0
                    ? 'positive'
                    : 'negative' }}">
                    {{ $persentasePenjualan >= 0 ? '+' : '' }}
                    {{ $persentasePenjualan }}%
                    dari periode sebelumnya
                </small>
            </article>

            <article class="report-summary-card">
                <span>Jumlah Transaksi</span>
                <strong>{{ $jumlahTransaksi }}</strong>

                <small class="{{ $persentaseTransaksi >= 0
                    ? 'positive'
                    : 'negative' }}">
                    {{ $persentaseTransaksi >= 0 ? '+' : '' }}
                    {{ $persentaseTransaksi }}%
                    dari periode sebelumnya
                </small>
            </article>

            <article class="report-summary-card">
                <span>Produk Terjual</span>
                <strong>{{ $produkTerjual }}</strong>

                <small class="{{ $persentaseProduk >= 0
                    ? 'positive'
                    : 'negative' }}">
                    {{ $persentaseProduk >= 0 ? '+' : '' }}
                    {{ $persentaseProduk }}%
                    dari periode sebelumnya
                </small>
            </article>
        </div>

        <div class="report-charts">
            <article class="report-card sales-chart-card">
                <div class="chart-heading">
                    <h2>Grafik Penjualan</h2>

                    <span>
                        {{ $tanggalMulai->translatedFormat('d M') }}
                        –
                        {{ $tanggalAkhir->translatedFormat('d M Y') }}
                    </span>
                </div>

                <div class="bar-chart">
                    @foreach($grafik as $data)
                        @php
                            $tinggi = $data['total'] > 0
                                ? max(
                                    8,
                                    ($data['total']
                                        / $nilaiGrafikTertinggi) * 100
                                )
                                : 3;
                        @endphp

                        <div class="bar-column">
                            <div class="bar-value">
                                @if($data['total'] > 0)
                                    Rp {{ number_format(
                                        $data['total'],
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                @endif
                            </div>

                            <div
                                class="chart-bar {{ $data['total'] == 0
                                    ? 'empty-bar'
                                    : '' }}"
                                style="height: {{ $tinggi }}%">
                            </div>

                            <span>{{ $data['tanggal'] }}</span>
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="report-card category-card">
                <h2>Kategori Produk</h2>

                <div class="donut-wrapper">
                    <div
                        class="donut-chart"
                        style="background: conic-gradient(
                            {{ $donutGradient }}
                        )">

                        <div class="donut-center">
                            <span>Total</span>
                            <strong>{{ $produkTerjual }}</strong>
                        </div>
                    </div>
                </div>

                <div class="category-list">
                    @forelse($kategoriProduk as $kategori)
                        <div class="category-row">
                            <div>
                                <span
                                    class="category-dot"
                                    style="background: {{ $kategori['warna'] }}">
                                </span>

                                {{ $kategori['nama'] }}
                            </div>

                            <strong>
                                {{ $kategori['persentase'] }}%
                            </strong>
                        </div>
                    @empty
                        <p class="empty-category">
                            Belum ada produk terjual pada periode ini.
                        </p>
                    @endforelse
                </div>
            </article>
        </div>
    </section>
</main>

</body>
</html>