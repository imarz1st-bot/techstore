<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Invoice - TechStore</title>

    <link rel="stylesheet" href="{{ asset('css/invoice.css') }}">
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

    <main class="invoice-page">
        <header class="invoice-heading">
            <span class="invoice-heading__icon" aria-hidden="true">&#10003;</span>
            <p class="invoice-heading__eyebrow">Pembayaran Anda telah dikonfirmasi</p>
            <h1 class="invoice-heading__title">Payment Successful</h1>
            <p class="invoice-heading__description">Terima kasih telah berbelanja di TechStore.</p>
        </header>

        <article class="invoice-card" aria-labelledby="invoice-title">
            <header class="invoice-card__header">
                <div>
                    <p class="invoice-card__eyebrow">TechStore</p>
                    <h2 class="invoice-card__title" id="invoice-title">Invoice</h2>
                </div>
                <span class="payment-status" role="status">
                    <span class="payment-status__indicator" aria-hidden="true"></span>
                    PAID
                </span>
            </header>

            <dl class="invoice-meta">
                <div class="invoice-meta__item">
                    <dt class="invoice-meta__label">Nomor Invoice</dt>
                    <dd class="invoice-meta__value">INV-20260927-001</dd>
                </div>
                <div class="invoice-meta__item">
                    <dt class="invoice-meta__label">Tanggal</dt>
                    <dd class="invoice-meta__value">{{ now()->format('d F Y') }}</dd>
                </div>
            </dl>

            <section class="buyer-details" aria-labelledby="buyer-details-title">
                <h3 class="invoice-section__title" id="buyer-details-title">Data Pembeli</h3>
                <dl class="buyer-details__list">
                    <div class="buyer-details__item">
                        <dt class="buyer-details__label">Nama</dt>
                        <dd class="buyer-details__value">{{ auth()->user()->name }}</dd>
                    </div>
                    <div class="buyer-details__item">
                        <dt class="buyer-details__label">Alamat Pengiriman</dt>
                        <dd class="buyer-details__value">Jl. Teknologi No. 27, Sukajadi, Bandung, Jawa Barat 40162</dd>
                    </div>
                </dl>
            </section>

            <section class="invoice-items" aria-labelledby="invoice-items-title">
                <h3 class="invoice-section__title" id="invoice-items-title">Rincian Pesanan</h3>
                <div class="invoice-table-wrap">
                    <table class="invoice-table">
                        <thead>
                            <tr>
                                <th scope="col">Produk</th>
                                <th scope="col">Kuantitas</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="invoice-table__product">APPLE 2020 Macbook Pro M1 <span>8 GB / 512 GB</span></td>
                                <td>1</td>
                                <td>Rp 15.999.999</td>
                                <td>Rp 15.999.999</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <dl class="invoice-total">
                <div class="invoice-total__row">
                    <dt class="invoice-total__label">Subtotal</dt>
                    <dd class="invoice-total__value">Rp 15.999.999</dd>
                </div>
                <div class="invoice-total__row">
                    <dt class="invoice-total__label">Pengiriman</dt>
                    <dd class="invoice-total__value">Gratis</dd>
                </div>
                <div class="invoice-total__row invoice-total__row--grand-total">
                    <dt class="invoice-total__label">Total Bayar</dt>
                    <dd class="invoice-total__value">Rp 15.999.999</dd>
                </div>
            </dl>

            <footer class="invoice-card__footer">
                <a class="invoice-action invoice-action--secondary" href="#" onclick="window.print(); return false;">
                    Print Invoice
                </a>
                <a class="invoice-action invoice-action--primary" href="{{ route('dashboard') }}">
                    Back to Dashboard
                </a>
            </footer>
        </article>
    </main>
</body>
</html>