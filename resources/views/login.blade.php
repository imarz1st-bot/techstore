<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - TechStore</title>

    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

<div class="login-page">

    <!-- BAGIAN KIRI -->
    <div class="login-card">

        <a href="{{ route('home') }}" class="brand">
            <div class="logo-icon">
                <div class="laptop-icon"></div>
            </div>

            <span>TechStore</span>
        </a>


        <h1>Masuk ke akun Anda</h1>


        <form action="{{ route('login.process') }}" method="POST">

    @csrf

    <div class="form-group">

        <label>Email</label>

        <div class="input-box">

            <span class="search-icon"></span>

            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Email"
                required
            >

        </div>

    </div>


    <div class="form-group">

        <label>Password</label>

        <div class="input-box">

            <span class="search-icon"></span>

            <input
                id="password"
                type="password"
                name="password"
                placeholder="Password"
                required
            >

            <button
                type="button"
                class="eye-button"
                onclick="showPassword()"
            >
                ◉
            </button>

        </div>

    </div>


    @if ($errors->any())

        <div class="error-message">
            {{ $errors->first() }}
        </div>

    @endif


    <div class="login-link">
        <a href="{{ route('register') }}">
            Belum punya Akun?
        </a>
    </div>


    <button type="submit" class="login-button">
        Login
    </button>

</form>

    </div>


    <!-- BAGIAN KANAN -->
    <div class="illustration">

        <div class="laptop">

            <div class="screen"></div>

            <div class="keyboard"></div>

        </div>


        <div class="plant">

            <div class="leaf leaf-left"></div>
            <div class="leaf leaf-right"></div>

            <div class="pot"></div>

        </div>

    </div>

</div>


<script>

function showPassword() {

    const password = document.getElementById("password");

    if (password.type === "password") {
        password.type = "text";
    } else {
        password.type = "password";
    }

}

</script>

</body>
</html>