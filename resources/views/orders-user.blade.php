<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Riwayat Pesanan - TechStore</title>

    <link rel="stylesheet"
          href="{{ asset('css/dashboard-user.css') }}">

    <style>
        .orders-page {
            max-width: 1100px;
            margin: 32px auto;
            padding: 0 20px;
        }

        .orders-heading {
            margin-bottom: 24px;
        }

        .orders-heading h1 {
            margin-bottom: 8px;
        }

        .orders-heading p {
            color: #64748b;
        }

        .order-history-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
        }

        .order-history-header,
        .order-history-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .order-history-number {
            margin: 0 0 6px;
            overflow-wrap: anywhere;
        }

        .order-history-date {
            color: #64748b;
            font-size: 14px;
        }

        .order-history-status {
            background: #f1f5f9;
            color: #334155;
            border-radius: 20px;
            padding: 6px 12px;
            font-size: 14px;
        }

        .order-history-items {
            list-style: none;
            padding: 16px 0;
            margin: 16px 0;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
        }

        .order-history-items li {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            padding: 8px 0;
        }

        .order-history-items small {
            display: block;
            color: #64748b;
            margin-top: 4px;
        }

        .order-history-link {
            display: inline-block;
            background: #2563eb;
            color: #fff;
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
        }

        .orders-empty {
            text-align: center;
            padding: 40px 20px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
        }

        .orders-pagination {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin: 24px 0;
        }

        .orders-pagination a {
            color: #2563eb;
        }
    </style>
</head>
<body>
    <header class="site-header">
        <a class="site-logo" href="{{ route('dashboard') }}">
            <span class="site-logo__mark" aria-hidden="true">T</span>
            <span class="site-logo__name">TechStore</span>
        </a>

        <div class="account-actions">
            <a href="{{ route('dashboard') }}">Beranda</a>

            <div class="account-profile">
                <span class="account-profile__avatar" aria-hidden="true">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </span>

                <span class="account-profile__name">
                    {{ auth()->user()->name }}
                </span>
            </div>

            <form class="logout-form"
                  action="{{ route('logout') }}"
                  method="POST">
                @csrf

                <button class="logout-form__button" type="submit">
                    Keluar
                </button>
            </form>
        </div>
    </header>

    <main class="orders-page">
        @if(session('success'))
            <div role="status"
                style="padding: 12px; margin-bottom: 16px;
                        background: #dcfce7; color: #166534;
                        border-radius: 8px;">
                {{ session('success') }}
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
        <div class="orders-heading">
            <h1>Riwayat Pesanan</h1>
            <p>Lihat status pesanan dan rincian pembelianmu.</p>
        </div>

        @forelse($orders as $order)
            <article class="order-history-card">
                <header class="order-history-header">
                    <div>
                        <h2 class="order-history-number">
                            {{ $order->nomor_pesanan }}
                        </h2>

                        <time class="order-history-date"
                              datetime="{{ $order->tanggal_pesanan->toIso8601String() }}">
                            {{ $order->tanggal_pesanan->format('d/m/Y H:i') }}
                        </time>
                        <p>
                            Pembayaran:
                            {{ ucfirst(str_replace('_', ' ', $order->status_pembayaran)) }}
                        </p>
                    </div>

                    <span class="order-history-status">
                        {{ ucfirst($order->status) }}
                    </span>
                </header>

                <ul class="order-history-items">
                    @foreach($order->items as $item)
                        <li>
                            <div>
                                <strong>{{ $item->nama_produk }}</strong>

                                <small>
                                    {{ $item->jumlah }} unit ×
                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                </small>
                            </div>

                            <span>
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </span>
                        </li>
                    @endforeach
                </ul>

                <footer class="order-history-footer">
                    <div>
                        Total Pesanan:
                        <strong>
                            Rp {{ number_format($order->total, 0, ',', '.') }}
                        </strong>
                    </div>

                    <a class="order-history-link"
                       href="{{ route('invoice', ['order' => $order->id]) }}">
                        Lihat Invoice
                    </a>
                    @if(
                        $order->status === 'menunggu'
                        && $order->status_pembayaran === 'belum_dibayar'
                    )
                        <form
                            action="{{ route('orders.cancel', ['order' => $order->id]) }}"
                            method="POST"
                            onsubmit="return confirm('Batalkan pesanan ini?');"
                        >
                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                style="background: #dc2626; color: white;
                                    border: 0; border-radius: 8px;
                                    padding: 10px 16px; cursor: pointer;"
                            >
                                Batalkan Pesanan
                            </button>
                        </form>
                    @endif
                </footer>
            </article>
        @empty
            <div class="orders-empty">
                <h2>Belum ada pesanan</h2>
                <p>Pesananmu akan muncul di sini setelah checkout.</p>

                <a class="order-history-link"
                   href="{{ route('dashboard') }}">
                    Mulai Belanja
                </a>
            </div>
        @endforelse

        @if($orders->hasPages())
            <nav class="orders-pagination" aria-label="Halaman riwayat pesanan">
                @if($orders->onFirstPage())
                    <span>Sebelumnya</span>
                @else
                    <a href="{{ $orders->previousPageUrl() }}">
                        Sebelumnya
                    </a>
                @endif

                <span>
                    Halaman {{ $orders->currentPage() }}
                    dari {{ $orders->lastPage() }}
                </span>

                @if($orders->hasMorePages())
                    <a href="{{ $orders->nextPageUrl() }}">
                        Berikutnya
                    </a>
                @else
                    <span>Berikutnya</span>
                @endif
            </nav>
        @endif
    </main>
</body>
</html>