<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pengaturan - LaptopStore</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-products.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-settings.css') }}">
</head>
<body>

<aside class="sidebar">
    <div>
        <div class="brand product-brand">
            <div class="brand-logo">
                @if($setting->logo)
                    <img
                        src="{{ asset('storage/' . $setting->logo) }}"
                        alt="{{ $setting->nama_toko }}"
                    >
                @else
                    <i class="bi bi-laptop"></i>
                @endif
            </div>

            <div>
                <h2>{{ $setting->nama_toko }}</h2>
                <span>Admin Panel</span>
            </div>
        </div>

        <nav class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="menu">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.products.index') }}" class="menu">
                <i class="bi bi-box-seam"></i>
                <span>Produk</span>
            </a>

            <a href="{{ route('admin.orders.index') }}" class="menu">
                <i class="bi bi-cart3"></i>
                <span>Pesanan</span>
            </a>

            <a href="{{ route('admin.customers.index') }}" class="menu">
                <i class="bi bi-people"></i>
                <span>Pelanggan</span>
            </a>

            <a href="{{ route('admin.reports.index') }}" class="menu">
                <i class="bi bi-bar-chart-fill"></i>
                <span>Laporan</span>
            </a>

            <a href="{{ route('admin.settings.index') }}"
               class="menu active">
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
        <div class="top-search">
            <i class="bi bi-search"></i>
            <span>Cari pengaturan...</span>
        </div>

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

    <section class="settings-content">
        <div class="settings-heading">
            <h1>Pengaturan</h1>
            <p>Atur informasi toko dan preferensi sistem</p>
        </div>

        <div class="settings-tabs">
            <button type="button" class="tab-button active">
                Informasi Toko
            </button>

            <button type="button"
                    class="tab-button"
                    onclick="alert('Pengaturan pembayaran dibuat setelah sistem checkout.')">
                Pembayaran
            </button>

            <button type="button"
                    class="tab-button"
                    onclick="alert('Pengaturan pengiriman dibuat setelah sistem checkout.')">
                Pengiriman
            </button>

            <button type="button"
                    class="tab-button"
                    onclick="alert('Pengaturan akun admin akan dibuat pada pengembangan berikutnya.')">
                Akun Admin
            </button>
        </div>

        @if(session('success'))
            <div class="alert-success">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        <form
            action="{{ route('admin.settings.update') }}"
            method="POST"
            enctype="multipart/form-data"
            class="settings-form">

            @csrf
            @method('PUT')

            <div class="settings-grid">
                <article class="settings-card store-information">
                    <h2>Informasi Toko</h2>

                    <div class="setting-field">
                        <label for="nama_toko">Nama Toko</label>

                        <input
                            type="text"
                            id="nama_toko"
                            name="nama_toko"
                            value="{{ old(
                                'nama_toko',
                                $setting->nama_toko
                            ) }}"
                        >

                        @error('nama_toko')
                            <small class="setting-error">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>

                    <div class="setting-field">
                        <label for="email">Email</label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old(
                                'email',
                                $setting->email
                            ) }}"
                        >

                        @error('email')
                            <small class="setting-error">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>

                    <div class="setting-field">
                        <label for="no_hp">No. HP</label>

                        <input
                            type="text"
                            id="no_hp"
                            name="no_hp"
                            value="{{ old(
                                'no_hp',
                                $setting->no_hp
                            ) }}"
                        >

                        @error('no_hp')
                            <small class="setting-error">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>

                    <div class="setting-field">
                        <label for="alamat">Alamat</label>

                        <textarea
                            id="alamat"
                            name="alamat"
                            rows="3"
                        >{{ old('alamat', $setting->alamat) }}</textarea>

                        @error('alamat')
                            <small class="setting-error">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>
                </article>

                <article class="settings-card store-logo">
                    <h2>Logo Toko</h2>

                    <label for="logo"
                           class="logo-preview"
                           id="logoPreview">

                        <img
                            id="previewImage"
                            src="{{ $setting->logo
                                ? asset('storage/' . $setting->logo)
                                : '' }}"
                            alt="Logo toko"
                            style="{{ $setting->logo
                                ? 'display:block;'
                                : 'display:none;' }}"
                        >

                        <div
                            id="logoPlaceholder"
                            class="logo-placeholder"
                            style="{{ $setting->logo
                                ? 'display:none;'
                                : 'display:grid;' }}">

                            <i class="bi bi-laptop"></i>
                        </div>

                        <input
                            type="file"
                            id="logo"
                            name="logo"
                            accept=".jpg,.jpeg,.png,.webp"
                        >
                    </label>

                    <label for="logo" class="change-logo-button">
                        Ubah Logo
                    </label>

                    <small class="logo-information">
                        Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                    </small>

                    @error('logo')
                        <small class="setting-error">
                            {{ $message }}
                        </small>
                    @enderror
                </article>
            </div>

            <button type="submit" class="save-settings-button">
                Simpan Perubahan
            </button>
        </form>
    </section>
</main>

<script>
    const inputLogo = document.getElementById('logo');
    const previewImage = document.getElementById('previewImage');
    const logoPlaceholder = document.getElementById('logoPlaceholder');

    inputLogo.addEventListener('change', function () {
        const file = this.files[0];

        if (!file || !file.type.startsWith('image/')) {
            return;
        }

        const reader = new FileReader();

        reader.onload = function (event) {
            previewImage.src = event.target.result;
            previewImage.style.display = 'block';
            logoPlaceholder.style.display = 'none';
        };

        reader.readAsDataURL(file);
    });
</script>

</body>
</html>