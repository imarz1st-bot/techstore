<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $product->nama }} - TechStore</title>

    <link rel="stylesheet" href="{{ asset('css/product-detail.css') }}">
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
                placeholder="Cari laptop dan aksesoris..."
                autocomplete="off"
            >
            <button class="site-search__button" type="submit">Cari</button>
        </form>

        <div class="account-actions">
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

    <main class="product-page">
        <nav class="product-breadcrumb" aria-label="Breadcrumb">
            <a class="product-breadcrumb__link" href="{{ route('dashboard') }}">Beranda</a>
            <span class="product-breadcrumb__separator" aria-hidden="true">/</span>
            <span class="product-breadcrumb__current" aria-current="page">Detail Produk</span>
        </nav>

        <article class="product-detail" aria-labelledby="product-title">
            <section class="product-gallery" aria-label="Gambar produk">
                <div class="product-gallery__accent" aria-hidden="true"></div>
                <figure class="product-gallery__figure">
                    <img
                        class="product-gallery__image"
                        src="{{ $product->gambar
                            ? asset('storage/' . $product->gambar)
                            : asset('images/laptop.png') }}"
                        alt="{{ $product->nama }}"
                    >
                    <figcaption class="product-gallery__caption">
                        {{ $product->nama }}
                    </figcaption>
            </figure>
            </section>

            <section class="product-information" aria-label="Informasi produk">
                <p class="product-information__eyebrow">
                    {{ $product->kategori }}
                </p>

                <h1 class="product-information__title" id="product-title">
                    {{ $product->nama }}
                </h1>

                <p class="product-information__summary"
                style="white-space: pre-line;">{{ $product->deskripsi ?: 'Belum ada deskripsi produk.' }}</p>

                <dl class="product-specifications">
                    <div class="product-specifications__item">
                        <dt class="product-specifications__label">Kategori</dt>
                        <dd class="product-specifications__value">
                            {{ $product->kategori }}
                        </dd>
                    </div>

                    <div class="product-specifications__item">
                        <dt class="product-specifications__label">Stok</dt>
                        <dd class="product-specifications__value">
                            {{ $product->stok }} unit
                        </dd>
                    </div>

                    <div class="product-specifications__item">
                        <dt class="product-specifications__label">Ketersediaan</dt>
                        <dd class="product-specifications__value">
                            {{ $product->stok > 0 ? 'Tersedia' : 'Stok habis' }}
                        </dd>
                    </div>
                </dl>

                <div class="product-price">
                    <span class="product-price__label">Harga</span>

                    <p class="product-price__amount">
                        Rp {{ number_format($product->harga, 0, ',', '.') }}
                    </p>
                </div>

                <div class="product-actions" aria-label="Aksi produk">
                    @if($product->stok > 0)
                        <a class="product-actions__buy"
                        href="{{ route('order', ['product_id' => $product->id]) }}">
                            Buy Now
                        </a>
                    @else
                        <span class="product-actions__buy"
                            aria-disabled="true"
                            style="opacity: 0.5; cursor: not-allowed;">
                            Stok Habis
                        </span>
                    @endif
                </div>
            </section>
        </article>
    </main>
</body>
</html>