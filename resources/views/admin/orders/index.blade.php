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
            <a href="{{ route('admin.dashboard') }}" class="menu">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.products.index') }}" class="menu">
                <i class="bi bi-box-seam"></i>
                <span>Produk</span>
            </a>

            <a href="{{ route('admin.orders.index') }}"
               class="menu active">
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
    <header class="topbar product-topbar">
        <form action="{{ route('admin.orders.index') }}"
              method="GET"
              class="top-search">

            <i class="bi bi-search"></i>

            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Cari pesanan..."
            >

            @if($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
        </form>

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

    <section class="orders-content">
        <div class="orders-heading">
            <h1>Data Pesanan</h1>
            <p>Kelola pesanan pelanggan</p>
        </div>

        @if(session('success'))
            <div class="alert-success">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.orders.index') }}"
              method="GET"
              class="order-filters">

            <div class="filter-field status-filter">
                <select name="status"
                        aria-label="Filter status"
                        onchange="this.form.submit()">

                    <option value="">Semua Status</option>

                    <option value="menunggu"
                        @selected($status === 'menunggu')>
                        Menunggu
                    </option>

                    <option value="diproses"
                        @selected($status === 'diproses')>
                        Diproses
                    </option>

                    <option value="dikirim"
                        @selected($status === 'dikirim')>
                        Dikirim
                    </option>

                    <option value="selesai"
                        @selected($status === 'selesai')>
                        Selesai
                    </option>

                    <option value="dibatalkan"
                        @selected($status === 'dibatalkan')>
                        Dibatalkan
                    </option>
                </select>
            </div>

            <div class="date-filter">
                <i class="bi bi-calendar3"></i>

                <input
                    type="date"
                    name="tanggal_mulai"
                    value="{{ $tanggalMulai }}"
                    aria-label="Tanggal mulai"
                >

                <span>—</span>

                <input
                    type="date"
                    name="tanggal_akhir"
                    value="{{ $tanggalAkhir }}"
                    aria-label="Tanggal akhir"
                >
            </div>

            <div class="filter-search">
                <i class="bi bi-search"></i>

                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari nomor pesanan..."
                >
            </div>

            <button type="submit" class="filter-button">
                Terapkan
            </button>

            @if($search || $status || $tanggalMulai || $tanggalAkhir)
                <a href="{{ route('admin.orders.index') }}"
                   class="reset-filter">
                    Reset
                </a>
            @endif
        </form>

        <div class="orders-table-card">
            <div class="table-responsive">
                <table class="orders-table">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>
                                {{ $orders->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <strong class="order-number">
                                    {{ $order->nomor_pesanan }}
                                </strong>
                            </td>

                            <td>{{ $order->nama_pelanggan }}</td>

                            <td>
                                Rp {{ number_format(
                                    $order->total,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </td>

                            <td>
                                <span class="order-status {{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>

                            <td class="order-date">
                                {{ $order->tanggal_pesanan
                                    ->translatedFormat('d M Y') }}
                            </td>

                            <td>
                                <div class="order-actions">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                    class="order-action view-action"
                                    title="Lihat detail">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="{{ route('admin.orders.show', $order) }}#ubah-status"
                                    class="order-action edit-action"
                                    title="Ubah status">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-orders">
                                <i class="bi bi-cart-x"></i>

                                @if($search || $status || $tanggalMulai || $tanggalAkhir)
                                    <p>Pesanan tidak ditemukan.</p>

                                    <a href="{{ route('admin.orders.index') }}">
                                        Tampilkan semua pesanan
                                    </a>
                                @else
                                    <p>Belum ada pesanan.</p>

                                    <span>
                                        Pesanan akan tampil setelah pembeli
                                        melakukan checkout.
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
                <div class="pagination-wrapper">
                    <span>
                        Menampilkan {{ $orders->firstItem() }}
                        sampai {{ $orders->lastItem() }}
                        dari {{ $orders->total() }} pesanan
                    </span>

                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </section>
</main>

</body>
</html>