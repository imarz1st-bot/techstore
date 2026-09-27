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

    <main class="storefront">
        <section class="hero-section" aria-labelledby="hero-title">
            <div class="hero-section__content">
                <p class="hero-section__eyebrow">Teknologi untuk produktivitas tanpa batas</p>
                <h1 class="hero-section__title" id="hero-title">PERFORMANCE<br>ULTRABOOK</h1>
                <p class="hero-section__description">
                    Ringan dibawa, bertenaga untuk setiap ide. Temukan laptop yang siap
                    menemani pekerjaan dan hiburan Anda.
                </p>
                <a class="hero-section__button" href="/product/detail">Buy Now</a>
            </div>

            <div class="hero-section__visual">
                <img
                    class="hero-section__image"
                    src="{{ asset('images/laptop.png') }}"
                    alt="Laptop ultrabook TechStore"
                >
            </div>
        </section>

        <section class="flash-sale-section" id="flash-sale" aria-labelledby="flash-sale-title">
            <div class="flash-sale-section__heading">
                <div>
                    <p class="flash-sale-section__eyebrow">Penawaran terbatas</p>
                    <h2 class="flash-sale-section__title" id="flash-sale-title">Flash Sale</h2>
                </div>
                <a class="flash-sale-section__link" href="#product-grid">Lihat produk</a>
            </div>

            <div class="product-grid" id="product-grid">
                <a class="product-card" href="/product/detail">
                    <div class="product-card__image-wrap">
                        <span class="product-card__badge">-15%</span>
                        <img class="product-card__image" src="{{ asset('images/laptop.png') }}" alt="Ultrabook" loading="lazy">
                    </div>
                    <div class="product-card__details">
                        <p class="product-card__category">Ultrabook</p>
                        <h3 class="product-card__name">Performance Ultrabook</h3>
                        <p class="product-card__price">Rp 12.750.000</p>
                    </div>
                </a>

                <a class="product-card" href="/product/detail">
                    <div class="product-card__image-wrap">
                        <span class="product-card__badge">-10%</span>
                        <img class="product-card__image" src="{{ asset('images/laptop.png') }}" alt="Laptop produktivitas" loading="lazy">
                    </div>
                    <div class="product-card__details">
                        <p class="product-card__category">Laptop</p>
                        <h3 class="product-card__name">Everyday Pro 14</h3>
                        <p class="product-card__price">Rp 9.499.000</p>
                    </div>
                </a>

                <a class="product-card" href="/product/detail">
                    <div class="product-card__image-wrap">
                        <span class="product-card__badge">-20%</span>
                        <img class="product-card__image" src="{{ asset('images/laptop.png') }}" alt="Laptop kreator" loading="lazy">
                    </div>
                    <div class="product-card__details">
                        <p class="product-card__category">Creator Series</p>
                        <h3 class="product-card__name">CreatorBook Studio</h3>
                        <p class="product-card__price">Rp 15.999.000</p>
                    </div>
                </a>

                <a class="product-card" href="/product/detail">
                    <div class="product-card__image-wrap">
                        <span class="product-card__badge">-12%</span>
                        <img class="product-card__image" src="{{ asset('images/laptop.png') }}" alt="Laptop gaming" loading="lazy">
                    </div>
                    <div class="product-card__details">
                        <p class="product-card__category">Gaming</p>
                        <h3 class="product-card__name">TechStore GameForce</h3>
                        <p class="product-card__price">Rp 18.250.000</p>
                    </div>
                </a>
            </div>
        </section>
    </main>
</body>
</html>