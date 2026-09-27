<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Your Order - TechStore</title>

    <link rel="stylesheet" href="{{ asset('css/order.css') }}">
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

    <main class="checkout-page">
        <header class="checkout-heading">
            <p class="checkout-heading__eyebrow">TechStore Checkout</p>
            <h1 class="checkout-heading__title">Your Order</h1>
            <p class="checkout-heading__description">Periksa pesanan dan lengkapi informasi pengiriman Anda.</p>
        </header>

        <div class="checkout-layout">
            <section class="order-summary" aria-labelledby="order-summary-title">
                <div class="order-summary__heading">
                    <h2 class="order-summary__title" id="order-summary-title">Ringkasan Pesanan</h2>
                    <span class="order-summary__item-count">1 produk</span>
                </div>

                <article class="order-item">
                    <div class="order-item__image-wrap">
                        <img
                            class="order-item__image"
                            src="{{ asset('images/laptop.png') }}"
                            alt="Apple 2020 Macbook Pro M1"
                        >
                    </div>

                    <div class="order-item__information">
                        <p class="order-item__category">Laptop Apple</p>
                        <h3 class="order-item__name">APPLE 2020 Macbook Pro M1</h3>
                        <p class="order-item__variant">8 GB / 512 GB</p>
                        <label class="order-item__quantity-label" for="order-quantity">Kuantitas</label>
                        <input
                            class="order-item__quantity"
                            id="order-quantity"
                            name="quantity"
                            type="number"
                            min="1"
                            value="1"
                        >
                    </div>

                    <p class="order-item__price">Rp 15.999.999</p>
                </article>

                <dl class="order-totals">
                    <div class="order-totals__row">
                        <dt class="order-totals__label">Subtotal</dt>
                        <dd class="order-totals__value">Rp 15.999.999</dd>
                    </div>
                    <div class="order-totals__row">
                        <dt class="order-totals__label">Pengiriman</dt>
                        <dd class="order-totals__value">Gratis</dd>
                    </div>
                    <div class="order-totals__row order-totals__row--grand-total">
                        <dt class="order-totals__label">Total Harga</dt>
                        <dd class="order-totals__value">Rp 15.999.999</dd>
                    </div>
                </dl>
            </section>

            <section class="checkout-panel" aria-labelledby="checkout-panel-title">
                <h2 class="checkout-panel__title" id="checkout-panel-title">Pembayaran &amp; Pengiriman</h2>

                <form class="checkout-form">
                    <fieldset class="shipping-details">
                        <legend class="checkout-form__legend">Alamat Pengiriman</legend>

                        <div class="checkout-field">
                            <label class="checkout-field__label" for="recipient-name">Nama penerima</label>
                            <input
                                class="checkout-field__input"
                                id="recipient-name"
                                name="recipient_name"
                                type="text"
                                placeholder="Nama lengkap penerima"
                                autocomplete="name"
                                required
                            >
                        </div>

                        <div class="checkout-field">
                            <label class="checkout-field__label" for="recipient-phone">Nomor telepon</label>
                            <input
                                class="checkout-field__input"
                                id="recipient-phone"
                                name="recipient_phone"
                                type="tel"
                                placeholder="08xx xxxx xxxx"
                                autocomplete="tel"
                                required
                            >
                        </div>

                        <div class="checkout-field">
                            <label class="checkout-field__label" for="shipping-address">Alamat lengkap</label>
                            <textarea
                                class="checkout-field__textarea"
                                id="shipping-address"
                                name="shipping_address"
                                rows="3"
                                placeholder="Jalan, nomor rumah, kecamatan, kota, kode pos"
                                autocomplete="street-address"
                                required
                            ></textarea>
                        </div>
                    </fieldset>

                    <fieldset class="payment-methods">
                        <legend class="checkout-form__legend">Metode Pembayaran</legend>

                        <label class="payment-option" for="payment-bank-transfer">
                            <input
                                class="payment-option__input"
                                id="payment-bank-transfer"
                                type="radio"
                                name="payment_method"
                                value="bank-transfer"
                                checked
                            >
                            <span class="payment-option__label">Transfer Bank</span>
                        </label>

                        <label class="payment-option" for="payment-ewallet">
                            <input
                                class="payment-option__input"
                                id="payment-ewallet"
                                type="radio"
                                name="payment_method"
                                value="e-wallet"
                            >
                            <span class="payment-option__label">E-Wallet</span>
                        </label>

                        <label class="payment-option" for="payment-credit-card">
                            <input
                                class="payment-option__input"
                                id="payment-credit-card"
                                type="radio"
                                name="payment_method"
                                value="credit-card"
                            >
                            <span class="payment-option__label">Kartu Kredit</span>
                        </label>
                    </fieldset>

                    <a class="checkout-form__submit" href="/invoice">Confirm Order</a>
                    <p class="checkout-form__note">Dengan melanjutkan, Anda menyetujui detail pesanan di atas.</p>
                </form>
            </section>
        </div>
    </main>
</body>
</html>