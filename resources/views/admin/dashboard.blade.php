<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin - LaptopStore</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
</head>
<body>

<aside class="sidebar">
    <div>
        <div class="brand">
            <h2>LaptopStore</h2>
            <span>ADMIN PANEL</span>
        </div>

        <nav class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="menu active">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.products.index') }}" class="menu">
                <i class="bi bi-box-seam"></i>
                <span>Data Produk</span>
            </a>

            <a href="{{ route('admin.orders.index') }}" class="menu">
                <i class="bi bi-cart3"></i>
                <span>Pesanan</span>
            </a>

            <a href="#" class="menu">
                <i class="bi bi-people"></i>
                <span>Data Pelanggan</span>
            </a>

            <a href="#" class="menu">
                <i class="bi bi-bar-chart"></i>
                <span>Laporan Penjualan</span>
            </a>

            <a href="#" class="menu">
                <i class="bi bi-gear"></i>
                <span>Pengaturan</span>
            </a>
        </nav>
    </div>

    <form action="{{ route('logout') }}" method="POST" class="logout-form">
        @csrf

        <button type="submit">
            <i class="bi bi-box-arrow-left"></i>
            <span>Logout</span>
        </button>
    </form>
</aside>

<main class="main-content">
    <header class="topbar">
        <h1>Dashboard</h1>

        <div class="admin-profile">
            <span>{{ auth()->user()->name }}</span>

            <div class="avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </div>
    </header>

    <section class="dashboard-content">
        <p class="subtitle">Ringkasan toko hari ini</p>

        <div class="summary-grid">
            <article class="summary-card">
                <span>Total Produk</span>
                <strong>{{ $summary['products'] }}</strong>
                <small>+8 produk bulan ini</small>
            </article>

            <article class="summary-card">
                <span>Pesanan</span>
                <strong>{{ $summary['orders'] }}</strong>
                <small>+12% dari bulan lalu</small>
            </article>

            <article class="summary-card">
                <span>Pelanggan</span>
                <strong>{{ $summary['customers'] }}</strong>
                <small>+24 pelanggan baru</small>
            </article>

            <article class="summary-card">
                <span>Pendapatan</span>
                <strong>{{ $summary['income'] }}</strong>
                <small>+18% bulan ini</small>
            </article>
        </div>

        <div class="dashboard-grid">
            <article class="panel sales-panel">
                <h3>Penjualan 7 Hari Terakhir</h3>
                <p>Jumlah transaksi</p>

                <div class="chart">
                    <svg viewBox="0 0 700 210"
                         preserveAspectRatio="none"
                         aria-label="Grafik penjualan">
                        <line x1="20" y1="35" x2="680" y2="35"
                              class="grid-line"/>
                        <line x1="20" y1="110" x2="680" y2="110"
                              class="grid-line"/>
                        <line x1="20" y1="185" x2="680" y2="185"
                              class="grid-line"/>

                        <polyline
                            points="40,155 145,125 250,140 355,70 460,90 565,25 665,55"
                            class="sales-line"/>

                        @foreach([
                            [40,155], [145,125], [250,140], [355,70],
                            [460,90], [565,25], [665,55]
                        ] as $point)
                            <circle cx="{{ $point[0] }}"
                                    cy="{{ $point[1] }}"
                                    r="5"
                                    class="sales-point"/>
                        @endforeach
                    </svg>

                    <div class="chart-days">
                        @foreach(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $day)
                            <span>{{ $day }}</span>
                        @endforeach
                    </div>
                </div>
            </article>

            <article class="panel orders-panel">
                <h3>Pesanan Terbaru</h3>

                <div class="order-list">
                    @foreach($latestOrders as $order)
                        <div class="order-row">
                            <strong>{{ $order['number'] }}</strong>
                            <span>{{ $order['customer'] }}</span>

                            <span class="status
                                {{ strtolower($order['status']) }}">
                                {{ $order['status'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </article>
        </div>

        <article class="panel best-products">
            <h3>Produk Terlaris</h3>

            @foreach($bestProducts as $product)
                <div class="product-row">
                    <span class="product-name">{{ $product['name'] }}</span>
                    <span>{{ $product['sold'] }} unit</span>

                    <div class="progress">
                        <div class="progress-value"
                             style="width: {{ $product['percentage'] }}%">
                        </div>
                    </div>

                    <span>{{ $product['income'] }}</span>
                </div>
            @endforeach
        </article>
    </section>
</main>

</body>
</html>