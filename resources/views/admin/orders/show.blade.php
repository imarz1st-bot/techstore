<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Detail {{ $order->nomor_pesanan }} - LaptopStore
    </title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-products.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-order-detail.css') }}">
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
    <header class="topbar product-topbar no-print">
        <div class="top-search">
            <i class="bi bi-search"></i>
            <span>Cari pesanan...</span>
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

    <section class="order-detail-content">
        <div class="detail-navigation no-print">
            <a href="{{ route('admin.orders.index') }}">
                <i class="bi bi-chevron-left"></i>
                Kembali
            </a>
        </div>

        <div class="detail-heading">
            <div class="heading-title">
                <h1>
                    Detail Pesanan {{ $order->nomor_pesanan }}
                </h1>

                <span class="detail-status {{ $order->status }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>

            <div class="heading-actions no-print">
                <a href="#ubah-status" class="status-link">
                    Ubah Status
                    <i class="bi bi-chevron-down"></i>
                </a>

                <button type="button"
                        class="print-button"
                        onclick="window.print()">
                    <i class="bi bi-download"></i>
                    Cetak Invoice
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success no-print">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="information-grid">
            <article class="detail-card customer-information">
                <h2>Informasi Pesanan</h2>

                <div class="customer-grid">
                    <div class="information-item">
                        <span>Nama Pelanggan</span>
                        <strong>{{ $order->nama_pelanggan }}</strong>
                    </div>

                    <div class="information-item">
                        <span>Email</span>
                        <strong>{{ $order->email }}</strong>
                    </div>

                    <div class="information-item">
                        <span>No. HP</span>
                        <strong>{{ $order->no_hp }}</strong>
                    </div>

                    <div class="information-item full-information">
                        <span>Alamat</span>
                        <strong>{{ $order->alamat }}</strong>
                    </div>

                    <div class="information-item">
                        <span>Tanggal Pesanan</span>
                        <strong>
                            {{ $order->tanggal_pesanan
                                ->translatedFormat('d M Y, H:i') }}
                        </strong>
                    </div>

                    <div class="information-item">
                        <span>Metode Pembayaran</span>
                        <strong>
                            {{ $order->metode_pembayaran
                                ?? 'Belum dipilih' }}
                        </strong>
                    </div>
                </div>
            </article>

            <article class="detail-card payment-summary">
                <h2>Ringkasan Pembayaran</h2>

                <div class="payment-row">
                    <span>Subtotal</span>
                    <strong>
                        Rp {{ number_format(
                            $order->subtotal,
                            0,
                            ',',
                            '.'
                        ) }}
                    </strong>
                </div>

                <div class="payment-row">
                    <span>Ongkos Kirim</span>
                    <strong>
                        Rp {{ number_format(
                            $order->ongkos_kirim,
                            0,
                            ',',
                            '.'
                        ) }}
                    </strong>
                </div>

                <div class="payment-total">
                    <span>Total Pembayaran</span>
                    <strong>
                        Rp {{ number_format(
                            $order->total,
                            0,
                            ',',
                            '.'
                        ) }}
                    </strong>
                </div>
            </article>
        </div>

        <article class="detail-card order-products">
            <div class="table-responsive">
                <table class="detail-table">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($order->items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                <div class="detail-product-image">
                                    @if($item->gambar)
                                        <img
                                            src="{{ asset('storage/' . $item->gambar) }}"
                                            alt="{{ $item->nama_produk }}"
                                        >
                                    @else
                                        <i class="bi bi-laptop"></i>
                                    @endif
                                </div>
                            </td>

                            <td>
                                <strong>{{ $item->nama_produk }}</strong>
                            </td>

                            <td>
                                Rp {{ number_format(
                                    $item->harga,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </td>

                            <td>{{ $item->jumlah }}</td>

                            <td>
                                <strong>
                                    Rp {{ number_format(
                                        $item->subtotal,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </strong>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-items">
                                Belum ada item dalam pesanan ini.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </article>

        <article class="detail-card status-section no-print"
                 id="ubah-status">
            <h2>Ubah Status Pesanan</h2>

            <form
                action="{{ route('admin.orders.update-status', $order) }}"
                method="POST"
                class="status-form">

                @csrf
                @method('PATCH')

                <div class="status-field">
                    <label for="status">Status</label>

                    <select name="status" id="status">
                        <option value="menunggu"
                            @selected($order->status === 'menunggu')>
                            Menunggu
                        </option>

                        <option value="diproses"
                            @selected($order->status === 'diproses')>
                            Diproses
                        </option>

                        <option value="dikirim"
                            @selected($order->status === 'dikirim')>
                            Dikirim
                        </option>

                        <option value="selesai"
                            @selected($order->status === 'selesai')>
                            Selesai
                        </option>

                        <option value="dibatalkan"
                            @selected($order->status === 'dibatalkan')>
                            Dibatalkan
                        </option>
                    </select>

                    @error('status')
                        <small class="input-error">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <button type="submit" class="update-status-button">
                    Simpan Status
                </button>
            </form>
        </article>
    </section>
</main>

</body>
</html>