<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Pesanan - LaptopStore</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-products.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-orders.css') }}">
</head>
<body>

<aside class="sidebar">
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
            <a href="{{ route('admin.dashboard') }}" class="menu active">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.products.index') }}" class="menu">
                <i class="bi bi-box-seam"></i>
                <span>Produk</span>
            </a>

            <a href="{{ route('admin.orders.index') }}"
               class="menu">
                <i class="bi bi-cart3"></i>
                <span>Pesanan</span>
            </a>

            <a href="{{ route('admin.customers.index') }}" class="menu">
                <i class="bi bi-people"></i>
                <span>Pelanggan</span>
            </a>

           <a href="{{ route('admin.reports.index') }}" class="menu">
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
    <header class="topbar">
        <h1>Dashboard</h1>

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

    <section class="dashboard-content">
        <p class="subtitle">Ringkasan seluruh data toko</p>

        <div class="summary-grid">
            <article class="summary-card">
                <span>Total Produk</span>
                <strong>{{ $summary['products'] }}</strong>
                <small>{{ $summary['products_month'] }} produk baru bulan ini</small>
            </article>

            <article class="summary-card">
                <span>Pesanan</span>
                <strong>{{ $summary['orders'] }}</strong>
                <small>{{ $summary['waiting_orders'] }} pesanan menunggu</small>
            </article>

            <article class="summary-card">
                <span>Pelanggan</span>
                <strong>{{ $summary['customers'] }}</strong>
                <small>{{ $summary['customers_month'] }} akun baru bulan ini</small>
            </article>

            <article class="summary-card">
                <span>Pendapatan</span>
                <strong>{{ $summary['income'] }}</strong>
                <small>Total pembayaran terverifikasi, termasuk Sandbox</small>
            </article>
        </div>

        <div class="dashboard-grid">
            <article class="panel sales-panel">
                <h3>Pembayaran Berhasil 7 Hari Terakhir</h3>
                <p>Jumlah transaksi terverifikasi per tanggal</p>

                <div class="chart">
                    <svg
                        viewBox="0 0 700 210"
                        preserveAspectRatio="none"
                        role="img"
                        aria-label="Grafik pembayaran berhasil tujuh hari terakhir"
                    >
                        <line x1="20" y1="35" x2="680" y2="35"
                            class="grid-line"/>

                        <line x1="20" y1="110" x2="680" y2="110"
                            class="grid-line"/>

                        <line x1="20" y1="185" x2="680" y2="185"
                            class="grid-line"/>

                        <polyline
                            points="{{ $salesPolyline }}"
                            class="sales-line"
                        />

                        @foreach($salesPoints as $point)
                            <circle
                                cx="{{ $point['x'] }}"
                                cy="{{ $point['y'] }}"
                                r="5"
                                class="sales-point"
                            >
                                <title>
                                    {{ $point['label'] }}:
                                    {{ $point['count'] }} transaksi
                                </title>
                            </circle>
                        @endforeach
                    </svg>

                    <div class="chart-days">
                        @foreach($salesDays as $day)
                            <span>
                                {{ $day['label'] }}
                                <br>
                                {{ $day['count'] }} transaksi
                            </span>
                        @endforeach
                    </div>
                </div>
            </article>

            <article class="panel orders-panel">
                <h3>Pesanan Terbaru</h3>

                <div class="order-list">
                    @forelse($latestOrders as $order)
                        <div class="order-row">
                            <a
                                href="{{ route('admin.orders.show', $order['id']) }}"
                                style="overflow-wrap: anywhere;"
                            >
                                <strong>{{ $order['number'] }}</strong>
                            </a>

                            <span>{{ $order['customer'] }}</span>

                            <span class="status {{ strtolower($order['status']) }}">
                                {{ $order['status'] }}
                            </span>
                        </div>
                    @empty
                        <p>Belum ada pesanan.</p>
                    @endforelse
                </div>
            </article>
        </div>

        <article class="panel best-products">
            <h3>Produk Terlaris</h3>
            <p>Berdasarkan jumlah unit dari pesanan yang sudah dibayar.</p>

            @forelse($bestProducts as $product)
                <div class="product-row">
                    <span class="product-name">
                        {{ $product['name'] }}
                    </span>

                    <span>{{ $product['sold'] }} unit</span>

                    <div class="progress">
                        <div
                            class="progress-value"
                            style="width: {{ $product['percentage'] }}%"
                        ></div>
                    </div>

                    <span>{{ $product['income'] }}</span>
                </div>
            @empty
                <p>Belum ada produk dari pesanan yang sudah dibayar.</p>
            @endforelse
        </article>
    </section>
</main>

</body>
</html>