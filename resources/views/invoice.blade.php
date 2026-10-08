<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Invoice {{ $order->nomor_pesanan }} - TechStore</title>

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
            <p class="invoice-heading__eyebrow">TechStore</p>

            <h1 class="invoice-heading__title">Detail Pesanan</h1>

            <p class="invoice-heading__description">
                Status pesanan: {{ ucfirst($order->status) }}.

                @if($order->status_pembayaran === 'paid')
                    Pembayaran telah diverifikasi melalui Midtrans.
                @else
                    Pembayaran belum terverifikasi lunas.
                @endif
            </p>

            @if(session('success'))
                <p role="status">{{ session('success') }}</p>
            @endif
        </header>

        <article class="invoice-card" aria-labelledby="invoice-title">
            <header class="invoice-card__header">
                <div>
                    <p class="invoice-card__eyebrow">TechStore</p>
                    <h2 class="invoice-card__title" id="invoice-title">Invoice</h2>
                    @if(session('payment_message'))
                        <div role="status"
                            style="padding: 12px; margin-bottom: 16px;
                                    background: #eff6ff; color: #1e40af;
                                    border-radius: 8px;">
                            {{ session('payment_message') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div role="alert"
                            style="padding: 12px; margin-bottom: 16px;
                                    background: #fff1f2; color: #b91c1c;
                                    border-radius: 8px;">
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    <p>
                        Status pembayaran:
                        <strong>
                            {{ $order->status_pembayaran === 'paid'
                                ? 'Lunas'
                                : ucfirst(str_replace('_', ' ', $order->status_pembayaran)) }}
                        </strong>
                    </p>

                    @if($order->paid_at)
                        <p>
                            Terverifikasi pada:
                            {{ $order->paid_at->format('d/m/Y H:i') }}
                        </p>
                    @endif
                </div>
                <span class="payment-status" role="status">
                    {{ strtoupper($order->status) }}
                </span>
            </header>

            <dl class="invoice-meta">
                <div class="invoice-meta__item">
                    <dt class="invoice-meta__label">Nomor Pesanan</dt>
                    <dd class="invoice-meta__value">
                        {{ $order->nomor_pesanan }}
                    </dd>
                </div>

                <div class="invoice-meta__item">
                    <dt class="invoice-meta__label">Tanggal Pesanan</dt>
                    <dd class="invoice-meta__value">
                        {{ $order->tanggal_pesanan->format('d/m/Y H:i') }}
                    </dd>
                </div>

                <div class="invoice-meta__item">
                    <dt class="invoice-meta__label">Metode Pembayaran</dt>
                    <dd class="invoice-meta__value">
                        {{ $order->metode_pembayaran
                        ? ucwords(str_replace('_', ' ', $order->metode_pembayaran))
                        : 'Belum terverifikasi — pilih metode melalui Midtrans' }}
                    </dd>
                </div>
            </dl>

            <section class="buyer-details" aria-labelledby="buyer-details-title">
                <h3 class="invoice-section__title" id="buyer-details-title">
                    Data Penerima
                </h3>

                <dl class="buyer-details__list">
                    <div class="buyer-details__item">
                        <dt class="buyer-details__label">Nama</dt>
                        <dd class="buyer-details__value">
                            {{ $order->nama_pelanggan }}
                        </dd>
                    </div>

                    <div class="buyer-details__item">
                        <dt class="buyer-details__label">Email</dt>
                        <dd class="buyer-details__value">
                            {{ $order->email }}
                        </dd>
                    </div>

                    <div class="buyer-details__item">
                        <dt class="buyer-details__label">Nomor Telepon</dt>
                        <dd class="buyer-details__value">
                            {{ $order->no_hp }}
                        </dd>
                    </div>

                    <div class="buyer-details__item">
                        <dt class="buyer-details__label">Alamat Pengiriman</dt>
                        <dd class="buyer-details__value"
                            style="white-space: pre-line;">{{ $order->alamat }}</dd>
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
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="invoice-table__product">
                                        {{ $item->nama_produk }}
                                    </td>

                                    <td>{{ $item->jumlah }}</td>

                                    <td>
                                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    </td>

                                    <td>
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            <dl class="invoice-total">
                <div class="invoice-total__row">
                    <dt class="invoice-total__label">Subtotal</dt>
                    <dd class="invoice-total__value">
                        Rp {{ number_format($order->subtotal, 0, ',', '.') }}
                    </dd>
                </div>

                <div class="invoice-total__row">
                    <dt class="invoice-total__label">Pengiriman</dt>
                    <dd class="invoice-total__value">
                        @if($order->ongkos_kirim == 0)
                            Gratis
                        @else
                            Rp {{ number_format($order->ongkos_kirim, 0, ',', '.') }}
                        @endif
                    </dd>
                </div>

                <div class="invoice-total__row invoice-total__row--grand-total">
                    <dt class="invoice-total__label">Total Pesanan</dt>
                    <dd class="invoice-total__value">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                    </dd>
                </div>
            </dl>

            <footer class="invoice-card__footer">
                <a class="invoice-action invoice-action--secondary" href="#" onclick="window.print(); return false;">
                    Print Invoice
                </a>
                <a class="invoice-action invoice-action--primary" href="{{ route('dashboard') }}">
                    Back to Dashboard
                </a>
                <a class="invoice-action invoice-action--secondary"
                href="{{ route('orders.index') }}">
                    Riwayat Pesanan
                </a>
                @if(
                    $order->status === 'menunggu'
                    && in_array(
                        $order->status_pembayaran,
                        ['belum_dibayar', 'pending'],
                        true
                    )
                )
                    <button
                        class="invoice-action invoice-action--primary"
                        id="pay-button"
                        type="button"
                        style="border: 0; cursor: pointer;"
                    >
                        {{ $order->snap_token ? 'Lanjutkan Pembayaran' : 'Bayar Sekarang' }}
                    </button>
                @endif
                @if(
                    $order->midtrans_order_id
                    && $order->status_pembayaran === 'pending'
                    && $order->status !== 'dibatalkan'
                )
                    <form
                        action="{{ route('orders.check-payment', ['order' => $order->id]) }}"
                        method="POST"
                    >
                        @csrf

                        <button
                            class="invoice-action invoice-action--secondary"
                            type="submit"
                            style="cursor: pointer;"
                        >
                            Cek Status Pembayaran
                        </button>
                    </form>
                @endif
            </footer>
        </article>
    </main>
    @if(
    $order->status === 'menunggu'
    && in_array(
        $order->status_pembayaran,
        ['belum_dibayar', 'pending'],
        true
    )
)
    <script
        src="{{ config('midtrans.is_production')
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ config('midtrans.client_key') }}"
    ></script>

    <script>
        const payButton = document.getElementById('pay-button');
        const originalText = payButton.textContent.trim();

        function resetPayButton() {
            payButton.disabled = false;
            payButton.textContent = originalText;
        }

        payButton.addEventListener('click', async function () {
            payButton.disabled = true;
            payButton.textContent = 'Memproses...';

            try {
                if (!window.snap) {
                    throw new Error(
                        'Script Midtrans belum termuat. Refresh halaman.'
                    );
                }

                const response = await fetch(
                    @json(route('orders.pay', ['order' => $order->id])),
                    {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document
                                .querySelector('meta[name="csrf-token"]')
                                .content
                        }
                    }
                );

                const data = await response.json();

                if (!response.ok) {
                    const errors = Object.values(data.errors || {}).flat();

                    throw new Error(
                        errors[0] || data.message || 'Pembayaran gagal dibuka.'
                    );
                }

                window.snap.pay(data.token, {
                    onSuccess: function () {
                        alert(
                            'Proses pembayaran selesai. '
                            + 'Status akan diperbarui setelah verifikasi server.'
                        );
                        window.location.reload();
                    },

                    onPending: function () {
                        alert('Pembayaran masih menunggu penyelesaian.');
                        window.location.reload();
                    },

                    onError: function () {
                        alert(
                            'Pembayaran belum berhasil. '
                            + 'Status transaksi perlu diperiksa.'
                        );
                        window.location.reload();
                    },

                    onClose: function () {
                        window.location.reload();
                    }
                });
            } catch (error) {
                alert(error.message);
                resetPayButton();
            }
        });
    </script>
@endif
</body>
</html>