<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Pelanggan - LaptopStore</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-products.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-customers.css') }}">
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

            <a href="{{ route('admin.orders.index') }}" class="menu">
                <i class="bi bi-cart3"></i>
                <span>Pesanan</span>
            </a>

            <a href="{{ route('admin.customers.index') }}"
               class="menu active">
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
        <form action="{{ route('admin.customers.index') }}"
              method="GET"
              class="top-search">

            <i class="bi bi-search"></i>

            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Cari pelanggan..."
            >

            @if($status)
                <input type="hidden"
                       name="status"
                       value="{{ $status }}">
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

    <section class="customers-content">
        <div class="customers-heading">
            <div>
                <h1>Data Pelanggan</h1>
                <p>Informasi pengguna yang terdaftar di sistem</p>
            </div>

            <form action="{{ route('admin.customers.index') }}"
                  method="GET"
                  class="customer-filter">

                @if($search)
                    <input type="hidden"
                           name="search"
                           value="{{ $search }}">
                @endif

                <select name="status"
                        onchange="this.form.submit()">
                    <option value="">Semua Status</option>

                    <option value="aktif"
                        @selected($status === 'aktif')>
                        Aktif
                    </option>

                    <option value="nonaktif"
                        @selected($status === 'nonaktif')>
                        Nonaktif
                    </option>
                </select>
            </form>
        </div>

        @if(session('success'))
            <div class="alert-success">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-error">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="customers-table-card">
            <div class="table-responsive">
                <table class="customers-table">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. HP</th>
                        <th>Tanggal Daftar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td>
                                {{ $customers->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <div class="customer-name">
                                    <div class="customer-avatar">
                                        {{ strtoupper(
                                            substr($customer->name, 0, 1)
                                        ) }}
                                    </div>

                                    <strong>{{ $customer->name }}</strong>
                                </div>
                            </td>

                            <td class="muted">
                                {{ $customer->email }}
                            </td>

                            <td class="muted">
                                {{ $customer->no_hp ?? '-' }}
                            </td>

                            <td class="muted">
                                {{ $customer->created_at
                                    ->translatedFormat('d M Y') }}
                            </td>

                            <td>
                                <span class="customer-status {{ $customer->status }}">
                                    {{ ucfirst($customer->status) }}
                                </span>
                            </td>

                            <td>
                                <div class="customer-actions">
                                    <form
                                        action="{{ route(
                                            'admin.customers.toggle-status',
                                            $customer
                                        ) }}"
                                        method="POST">

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="customer-action status-action"
                                            title="{{ $customer->status === 'aktif'
                                                ? 'Nonaktifkan pelanggan'
                                                : 'Aktifkan pelanggan' }}"
                                            onclick="return confirm(
                                                'Yakin ingin mengubah status pelanggan ini?'
                                            )">

                                            @if($customer->status === 'aktif')
                                                <i class="bi bi-person-slash"></i>
                                            @else
                                                <i class="bi bi-person-check"></i>
                                            @endif
                                        </button>
                                    </form>

                                    <form
                                        action="{{ route(
                                            'admin.customers.destroy',
                                            $customer
                                        ) }}"
                                        method="POST"
                                        onsubmit="return confirm(
                                            'Yakin ingin menghapus pelanggan {{ $customer->name }}?'
                                        )">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="customer-action delete-action"
                                            title="Hapus pelanggan">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-customers">
                                <i class="bi bi-people"></i>

                                @if($search || $status)
                                    <p>Pelanggan tidak ditemukan.</p>

                                    <a href="{{ route('admin.customers.index') }}">
                                        Tampilkan semua pelanggan
                                    </a>
                                @else
                                    <p>Belum ada pelanggan terdaftar.</p>

                                    <span>
                                        Pelanggan akan tampil setelah membuat akun.
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($customers->hasPages())
                <div class="pagination-wrapper">
                    <span>
                        Menampilkan {{ $customers->firstItem() }}
                        sampai {{ $customers->lastItem() }}
                        dari {{ $customers->total() }} pelanggan
                    </span>

                    {{ $customers->links() }}
                </div>
            @endif
        </div>
    </section>
</main>

</body>
</html>