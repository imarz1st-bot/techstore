<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Produk - LaptopStore</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-products.css') }}">
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

            <a href="{{ route('admin.products.index') }}"
               class="menu active">
                <i class="bi bi-box-seam"></i>
                <span>Produk</span>
            </a>

            <a href="#" class="menu">
                <i class="bi bi-cart3"></i>
                <span>Pesanan</span>
            </a>

            <a href="#" class="menu">
                <i class="bi bi-people"></i>
                <span>Pelanggan</span>
            </a>

            <a href="#" class="menu">
                <i class="bi bi-bar-chart-fill"></i>
                <span>Laporan</span>
            </a>

            <a href="#" class="menu">
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
        <form action="{{ route('admin.products.index') }}"
              method="GET"
              class="top-search">

            <i class="bi bi-search"></i>

            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Cari produk..."
                autocomplete="off"
            >
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

    <section class="products-content">
        <div class="page-heading">
            <div>
                <h1>Data Produk</h1>
                <p>Kelola produk laptop yang tersedia di toko</p>
            </div>

            {{-- Route tambah produk dibuat pada tahap selanjutnya --}}
            <a href="{{ route('admin.products.create') }}"
                class="add-product-button">
                <i class="bi bi-plus-lg"></i>
                Tambah Produk
            </a>
        </div>

        @if(session('success'))
            <div class="alert-success">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="product-table-card">
            <div class="table-responsive">
                <table class="product-table">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                {{ $products->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <div class="product-image">
                                    @if($product->gambar)
                                        <img
                                            src="{{ asset('storage/' . $product->gambar) }}"
                                            alt="{{ $product->nama }}"
                                        >
                                    @else
                                        <i class="bi bi-laptop"></i>
                                    @endif
                                </div>
                            </td>

                            <td>
                                <strong>{{ $product->nama }}</strong>
                            </td>

                            <td class="muted">
                                {{ $product->kategori }}
                            </td>

                            <td>
                                Rp {{ number_format($product->harga, 0, ',', '.') }}
                            </td>

                            <td>{{ $product->stok }}</td>

                            <td>
                                <span class="status-badge {{ $product->status }}">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>

                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                    class="action-button edit-button"
                                    title="Edit produk">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form
                                        action="{{ route('admin.products.destroy', $product) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus produk {{ $product->nama }}?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="action-button delete-button"
                                                title="Hapus produk">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-data">
                                <i class="bi bi-box-seam"></i>

                                @if($search)
                                    <p>
                                        Produk dengan kata
                                        “{{ $search }}” tidak ditemukan.
                                    </p>

                                    <a href="{{ route('admin.products.index') }}">
                                        Tampilkan semua produk
                                    </a>
                                @else
                                    <p>Belum ada produk di database.</p>
                                    <span>
                                        Tambahkan data contoh menggunakan seeder di bawah.
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
                <div class="pagination-wrapper">
                    <span>
                        Menampilkan {{ $products->firstItem() }}
                        sampai {{ $products->lastItem() }}
                        dari {{ $products->total() }} produk
                    </span>

                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </section>
</main>

</body>
</html>