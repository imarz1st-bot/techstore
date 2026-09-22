<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Produk - LaptopStore</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-products.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-product-form.css') }}">
</head>
<body>

<aside class="sidebar">
    <div>
        <div class="brand product-brand">
            <div class="brand-logo">
                <i class="bi bi-laptop"></i>
            </div>

            <div>
                <h2>LaptopStore</h2>
                <span>Admin Panel</span>
            </div>
        </div>

        <nav class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="menu">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.products.index') }}"
               class="menu active">
                <i class="bi bi-box-seam"></i>
                <span>Produk</span>
            </a>

            <a href="#" class="menu">
                <i class="bi bi-cart3"></i>
                <span>Pesanan</span>
            </a>

            <a href="#" class="menu">
                <i class="bi bi-people"></i>
                <span>Pelanggan</span>
            </a>

            <a href="#" class="menu">
                <i class="bi bi-bar-chart-fill"></i>
                <span>Laporan</span>
            </a>

            <a href="#" class="menu">
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
            <span>Cari produk...</span>
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

    <section class="product-form-content">
        <div class="form-heading">
            <h1>Tambah Produk</h1>
            <p>Isi informasi produk dengan lengkap</p>
        </div>

        <form action="{{ route('admin.products.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="product-form">

            @csrf

            <div class="form-layout">
                <div class="form-fields">
                    <div class="form-group full-width">
                        <label for="nama">Nama Produk <b>*</b></label>

                        <input
                            type="text"
                            id="nama"
                            name="nama"
                            value="{{ old('nama') }}"
                            placeholder="Masukkan nama produk"
                        >

                        @error('nama')
                            <small class="input-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="two-columns">
                        <div class="form-group">
                            <label for="kategori">Kategori <b>*</b></label>

                            <select id="kategori" name="kategori">
                                <option value="">Pilih kategori</option>

                                <option value="Laptop"
                                    @selected(old('kategori') === 'Laptop')>
                                    Laptop
                                </option>

                                <option value="Gaming"
                                    @selected(old('kategori') === 'Gaming')>
                                    Gaming
                                </option>

                                <option value="MacBook"
                                    @selected(old('kategori') === 'MacBook')>
                                    MacBook
                                </option>

                                <option value="Aksesoris"
                                    @selected(old('kategori') === 'Aksesoris')>
                                    Aksesoris
                                </option>
                            </select>

                            @error('kategori')
                                <small class="input-error">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="stok">Stok <b>*</b></label>

                            <input
                                type="number"
                                id="stok"
                                name="stok"
                                value="{{ old('stok') }}"
                                min="0"
                                placeholder="Jumlah stok"
                            >

                            @error('stok')
                                <small class="input-error">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group price-field">
                        <label for="harga">Harga <b>*</b></label>

                        <div class="price-input">
                            <span>Rp</span>

                            <input
                                type="number"
                                id="harga"
                                name="harga"
                                value="{{ old('harga') }}"
                                min="0"
                                step="1"
                                placeholder="Contoh: 12499000"
                            >
                        </div>

                        @error('harga')
                            <small class="input-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group full-width">
                        <label for="deskripsi">Deskripsi</label>

                        <textarea
                            id="deskripsi"
                            name="deskripsi"
                            rows="5"
                            placeholder="Masukkan deskripsi produk..."
                        >{{ old('deskripsi') }}</textarea>

                        @error('deskripsi')
                            <small class="input-error">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="image-field">
                    <label>Gambar Produk <b>*</b></label>

                    <label for="gambar"
                           class="image-upload"
                           id="imageUpload">

                        <img id="imagePreview"
                             src=""
                             alt="Pratinjau gambar">

                        <div class="upload-placeholder"
                             id="uploadPlaceholder">
                            <i class="bi bi-upload"></i>

                            <p>Klik untuk mengunggah gambar</p>
                            <span>atau drag and drop</span>
                        </div>

                        <input
                            type="file"
                            id="gambar"
                            name="gambar"
                            accept=".jpg,.jpeg,.png,.webp"
                        >
                    </label>

                    <small class="image-information">
                        Format JPG, JPEG, PNG, atau WEBP (maksimal 2 MB)
                    </small>

                    @error('gambar')
                        <small class="input-error image-error">
                            {{ $message }}
                        </small>
                    @enderror

                    <button type="button"
                            id="removeImage"
                            class="remove-image-button">
                        <i class="bi bi-trash"></i>
                        Hapus gambar
                    </button>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.products.index') }}"
                   class="cancel-button">
                    Batal
                </a>

                <button type="submit" class="save-button">
                    Simpan
                </button>
            </div>
        </form>
    </section>
</main>

<script>
    const inputGambar = document.getElementById('gambar');
    const imagePreview = document.getElementById('imagePreview');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const removeImageButton = document.getElementById('removeImage');
    const imageUpload = document.getElementById('imageUpload');

    function tampilkanGambar(file) {
        if (!file || !file.type.startsWith('image/')) {
            return;
        }

        const reader = new FileReader();

        reader.onload = function (event) {
            imagePreview.src = event.target.result;
            imagePreview.style.display = 'block';
            uploadPlaceholder.style.display = 'none';
            removeImageButton.style.display = 'inline-flex';
        };

        reader.readAsDataURL(file);
    }

    inputGambar.addEventListener('change', function () {
        tampilkanGambar(this.files[0]);
    });

    removeImageButton.addEventListener('click', function () {
        inputGambar.value = '';
        imagePreview.src = '';
        imagePreview.style.display = 'none';
        uploadPlaceholder.style.display = 'flex';
        removeImageButton.style.display = 'none';
    });

    imageUpload.addEventListener('dragover', function (event) {
        event.preventDefault();
        imageUpload.classList.add('dragging');
    });

    imageUpload.addEventListener('dragleave', function () {
        imageUpload.classList.remove('dragging');
    });

    imageUpload.addEventListener('drop', function (event) {
        event.preventDefault();
        imageUpload.classList.remove('dragging');

        const file = event.dataTransfer.files[0];

        if (!file) {
            return;
        }

        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        inputGambar.files = dataTransfer.files;

        tampilkanGambar(file);
    });
</script>

</body>
</html>