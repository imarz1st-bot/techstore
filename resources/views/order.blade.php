<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Your Order - TechStore</title>

    <link rel="stylesheet" href="{{ asset('css/order.css') }}">
</head>
<script>
    const quantityInput = document.getElementById('order-quantity');
    const subtotalElement = document.getElementById('order-subtotal');
    const totalElement = document.getElementById('order-total');

    const price = Number(@json($product->harga));
    const stock = Number(@json($product->stok));
    const shippingFee = Number(@json($shippingFee));

    const rupiah = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0
    });

    function updateTotal() {
        const quantity = quantityInput.valueAsNumber;

        const valid = Number.isInteger(quantity)
            && quantity >= 1
            && quantity <= stock;

        quantityInput.setCustomValidity(
            valid ? '' : `Jumlah pembelian harus 1 sampai ${stock}.`
        );

        if (!valid) {
            subtotalElement.textContent = 'Jumlah tidak valid';
            totalElement.textContent = 'Jumlah tidak valid';
            return;
        }

        const subtotal = price * quantity;

        subtotalElement.textContent = rupiah.format(subtotal);
        totalElement.textContent = rupiah.format(subtotal + shippingFee);
    }

    quantityInput.addEventListener('input', updateTotal);
    updateTotal();
</script>
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
                                @if($errors->any())
                    <div role="alert"
                        style="padding: 12px; margin-bottom: 16px;
                                background: #fff1f2; color: #b91c1c;
                                border-radius: 8px;">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
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
                            src="{{ $product->gambar
                                ? asset('storage/' . $product->gambar)
                                : asset('images/laptop.png') }}"
                            alt="{{ $product->nama }}"
                        >
                    </div>

                    <div class="order-item__information">
                        <p class="order-item__category">
                            {{ $product->kategori }}
                        </p>

                        <h3 class="order-item__name">
                            {{ $product->nama }}
                        </h3>

                        <p class="order-item__variant">
                            Stok tersedia: {{ $product->stok }} unit
                        </p>

                        <label class="order-item__quantity-label"
                            for="order-quantity">
                            Kuantitas
                        </label>

                        <input
                            class="order-item__quantity"
                            id="order-quantity"
                            name="quantity"
                            form="checkout-form"
                            type="number"
                            min="1"
                            max="{{ $product->stok }}"
                            step="1"
                            value="{{ old('quantity', 1) }}"
                            required
                        >
                    </div>

                    <p class="order-item__price">
                        Rp {{ number_format($product->harga, 0, ',', '.') }}
                    </p>
                </article>

                <dl class="order-totals">
                    <div class="order-totals__row">
                        <dt class="order-totals__label">Subtotal</dt>
                        <dd class="order-totals__value" id="order-subtotal">
                            Rp {{ number_format($product->harga, 0, ',', '.') }}
                        </dd>
                    </div>

                    <div class="order-totals__row">
                        <dt class="order-totals__label">Pengiriman</dt>
                        <dd class="order-totals__value">
                            {{ $shippingFee === 0
                                ? 'Gratis'
                                : 'Rp ' . number_format($shippingFee, 0, ',', '.') }}
                        </dd>
                    </div>

                    <div class="order-totals__row order-totals__row--grand-total">
                        <dt class="order-totals__label">Total Harga</dt>
                        <dd class="order-totals__value" id="order-total">
                            Rp {{ number_format($product->harga + $shippingFee, 0, ',', '.') }}
                        </dd>
                    </div>
                </dl>
            </section>

            <section class="checkout-panel" aria-labelledby="checkout-panel-title">
                <h2 class="checkout-panel__title" id="checkout-panel-title">Pembayaran &amp; Pengiriman</h2>

                <form class="checkout-form" id="checkout-form" action="{{ route('order.store') }}" method="POST">
                    @csrf
                                        @if($errors->any())
                        <div role="alert"
                            style="padding: 12px; margin-bottom: 16px;
                                    background: #fff1f2; color: #b91c1c;
                                    border-radius: 8px;">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <input type="hidden"
                        name="product_id"
                        value="{{ $product->id }}">
                    <input
                        type="hidden"
                        name="shipping_fee"
                        value="{{ $shippingFee }}"
                    >
                    <fieldset class="shipping-details">
                        <legend class="checkout-form__legend">Alamat Pengiriman</legend>

                        <div class="checkout-field">
                            <label class="checkout-field__label" for="recipient-name">Nama penerima</label>
                            <input
                                class="checkout-field__input"
                                id="recipient-name"
                                name="recipient_name"
                                type="text"
                                value="{{ old('recipient_name', auth()->user()->name) }}"
                                maxlength="255"
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
                                value="{{ old('recipient_phone', auth()->user()->no_hp) }}"
                                maxlength="25"
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
                                maxlength="2000"
                                placeholder="Jalan, nomor rumah, kecamatan, kota, kode pos"
                                autocomplete="street-address"
                                required
                            >{{ old('shipping_address') }}</textarea>
                        </div>
                    </fieldset>

                    <div class="payment-information">
                        <h3 class="checkout-form__legend">Pembayaran</h3>

                        <p>
                            Metode pembayaran dipilih melalui Midtrans
                            setelah pesanan dikonfirmasi.
                        </p>
                    </div>

                    <button class="checkout-form__submit" type="submit">
                        Confirm Order
                    </button>
                    <p class="checkout-form__note">Dengan melanjutkan, Anda menyetujui detail pesanan di atas.</p>
                </form>
            </section>
        </div>
    </main>
</body>
</html>