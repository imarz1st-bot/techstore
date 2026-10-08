<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Beranda - TechStore</title>

    <link rel="stylesheet" href="{{ asset('css/dashboard-user.css') }}">
</head>
<body>
    <header class="site-header">
        <a class="site-logo" href="{{ route('dashboard') }}" aria-label="TechStore, beranda">
            <span class="site-logo__mark" aria-hidden="true">T</span>
            <span class="site-logo__name">TechStore</span>
        </a>

        <form class="site-search" action="{{ route('dashboard') }}" method="GET" role="search">
            <label class="visually-hidden" for="product-search">Cari produk</label>
            <input
                class="site-search__input"
                id="product-search"
                type="search"
                name="search"
                value="{{ $search }}"
                placeholder="Cari laptop dan aksesoris..."
                autocomplete="off"
            >
            <button class="site-search__button" type="submit">Cari</button>
        </form>

        <div class="account-actions">
            <a href="{{ route('orders.index') }}"
            style="text-decoration: none; color: #2563eb; font-weight: 600;">
                Pesanan Saya
            </a>
            <div class="account-profile">
                <span class="account-profile__avatar" aria-hidden="true">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </span>
                <span class="account-profile__name">{{ auth()->user()->name }}</span>
            </div>

            <form class="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="logout-form__button" type="submit">Keluar</button>
            </form>
        </div>
    </header>

    <main class="storefront">
        @if(session('success'))
            <div role="status"
                style="padding: 14px; margin-bottom: 20px;
                        background: #dcfce7; color: #166534;
                        border-radius: 8px;">
                {{ session('success') }}
            </div>
        @endif
        <section class="hero-section" aria-labelledby="hero-title">
            <div class="hero-section__content">
                <p class="hero-section__eyebrow">Teknologi untuk produktivitas tanpa batas</p>
                <h1 class="hero-section__title" id="hero-title">PERFORMANCE<br>ULTRABOOK</h1>
                <p class="hero-section__description">
                    Ringan dibawa, bertenaga untuk setiap ide. Temukan laptop yang siap
                    menemani pekerjaan dan hiburan Anda.
                </p>
                <a class="hero-section__button" href="#product-grid">Lihat Produk</a>
            </div>
            <div class="hero-section__visual">
                <img
                    class="hero-section__image"
                    src="{{ asset('images/laptop.png') }}"
                    alt="Laptop ultrabook TechStore"
                >
            </div>
        </section>

        <div id="product-grid">
            @if($search !== '')
                <div class="category-search-summary">
                    <p>Hasil pencarian: <strong>{{ $search }}</strong></p>

                    <a href="{{ route('dashboard') }}">
                        Tampilkan semua produk
                    </a>
                </div>
            @endif

            @forelse($categories as $categoryKey => $categoryName)
                @php
                    $categoryProducts = $productsByCategory->get(
                        $categoryKey,
                        collect()
                    );
                @endphp

                @if($categoryProducts->isNotEmpty())
                    <section
                        class="flash-sale-section category-section"
                        id="category-{{ $categoryKey }}"
                        aria-labelledby="title-{{ $categoryKey }}"
                    >
                        <div class="flash-sale-section__heading">
                            <div>
                                <p class="flash-sale-section__eyebrow">
                                    Koleksi TechStore
                                </p>

                                <h2
                                    class="flash-sale-section__title"
                                    id="title-{{ $categoryKey }}"
                                >
                                    {{ $categoryName }}
                                </h2>
                            </div>

                            <span class="category-product-count">
                                {{ $categoryProducts->count() }} produk
                            </span>
                        </div>

                        <div class="product-grid category-product-grid">
                            @foreach($categoryProducts as $product)
                                <a
                                    class="product-card"
                                    href="{{ route('product.detail', ['id' => $product->id]) }}"
                                >
                                    <div class="product-card__image-wrap">
                                        @if($product->stok <= 0)
                                            <span class="product-card__badge">
                                                Stok habis
                                            </span>
                                        @endif

                                        @if($product->gambar)
                                            <img
                                                class="product-card__image"
                                                src="{{ asset('storage/' . $product->gambar) }}"
                                                alt="{{ $product->nama }}"
                                                loading="lazy"
                                            >
                                        @else
                                            <div class="product-image-placeholder">
                                                Gambar belum tersedia
                                            </div>
                                        @endif
                                    </div>

                                    <div class="product-card__details">
                                        <p class="product-card__category">
                                            {{ $categoryName }}
                                        </p>

                                        <h3 class="product-card__name">
                                            {{ $product->nama }}
                                        </h3>

                                        <p class="product-card__price">
                                            Rp {{ number_format($product->harga, 0, ',', '.') }}
                                        </p>

                                        <p class="category-product-stock">
                                            Stok: {{ $product->stok }} unit
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif
            @empty
                {{-- Daftar kategori ditentukan oleh controller. --}}
            @endforelse

            @if($productsByCategory->isEmpty())
                <div class="category-empty">
                    {{ $search !== ''
                        ? 'Produk yang kamu cari tidak ditemukan.'
                        : 'Belum ada produk aktif yang tersedia.' }}
                </div>
            @endif
        </div>
    </main>
</body>
</html>