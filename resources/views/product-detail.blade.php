<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Detail Produk - TechStore</title>

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
                        src="{{ asset('images/laptop.png') }}"
                        alt="Apple 2020 Macbook Pro M1 warna silver"
                    >
                    <figcaption class="product-gallery__caption">APPLE MACBOOK PRO M1</figcaption>
                </figure>
            </section>

            <section class="product-information" aria-label="Informasi produk">
                <p class="product-information__eyebrow">Laptop Apple</p>
                <h1 class="product-information__title" id="product-title">
                    APPLE 2020 Macbook Pro M1 8 GB / 512 GB
                </h1>

                <div class="product-rating" aria-label="Rating 4,5 dari 5 bintang, 2.372 ulasan">
                    <span class="product-rating__stars" aria-hidden="true">&#9733;&#9733;&#9733;&#9733;&#9734;</span>
                    <span class="product-rating__score">4,5 Bintang</span>
                    <span class="product-rating__separator" aria-hidden="true">|</span>
                    <a class="product-rating__reviews" href="#product-reviews">2.372 ulasan</a>
                </div>

                <p class="product-information__summary">
                    MacBook Pro dengan chip Apple M1 menghadirkan performa cepat,
                    baterai tahan lama, dan desain ringkas untuk bekerja maupun berkarya.
                </p>

                <dl class="product-specifications">
                    <div class="product-specifications__item">
                        <dt class="product-specifications__label">Prosesor</dt>
                        <dd class="product-specifications__value">Apple M1</dd>
                    </div>
                    <div class="product-specifications__item">
                        <dt class="product-specifications__label">Memori</dt>
                        <dd class="product-specifications__value">8 GB unified memory</dd>
                    </div>
                    <div class="product-specifications__item">
                        <dt class="product-specifications__label">Penyimpanan</dt>
                        <dd class="product-specifications__value">512 GB SSD</dd>
                    </div>
                    <div class="product-specifications__item">
                        <dt class="product-specifications__label">Layar</dt>
                        <dd class="product-specifications__value">13,3 inci Retina</dd>
                    </div>
                </dl>

                <div class="product-price">
                    <span class="product-price__label">Harga</span>
                    <p class="product-price__amount">Rp 15.999.999</p>
                </div>

                <div class="product-actions" aria-label="Aksi produk">
                    <a class="product-actions__buy" href="/order">Buy Now</a>
                    <button class="product-actions__cart" type="button">Add to Cart</button>
                    <button
                        class="product-actions__bookmark"
                        type="button"
                        aria-label="Tambahkan produk ke favorit"
                        aria-pressed="false"
                    >
                        <span aria-hidden="true">&#9734;</span>
                    </button>
                </div>
            </section>
        </article>

        <section class="product-reviews" id="product-reviews" aria-labelledby="product-reviews-title">
            <h2 class="product-reviews__title" id="product-reviews-title">Ulasan pelanggan</h2>
            <p class="product-reviews__summary">2.372 ulasan untuk produk ini</p>
        </section>
    </main>
</body>
</html>