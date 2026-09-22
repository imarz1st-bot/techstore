<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register - TechStore</title>

    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>

<body>

<div class="register-page">

    <!-- FORM REGISTER -->
    <div class="register-card">

        <!-- LOGO -->
        <a href="{{ route('home') }}" class="brand">

            <div class="logo-icon">
                <div class="laptop-icon"></div>
            </div>

            <span>TechStore</span>

        </a>


        <h1>Buat akun Anda</h1>


            <form action="{{ route('register.process') }}" method="POST">

        @csrf

        <div class="form-group">
            <label>Username</label>

            <div class="input-box">
                <span class="search-icon"></span>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Username"
                    required
                >
            </div>
        </div>


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
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Password"
                    required
                >

                <button
                    type="button"
                    class="eye-button"
                    onclick="togglePassword('password')"
                >
                    ◉
                </button>
            </div>
        </div>


        <div class="form-group">
            <label>Confirmed Password</label>

            <div class="input-box">
                <span class="search-icon"></span>

                <input
                    type="password"
                    name="password_confirmation"
                    id="confirmPassword"
                    placeholder="Confirmed Password"
                    required
                >

                <button
                    type="button"
                    class="eye-button"
                    onclick="togglePassword('confirmPassword')"
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
            <a href="{{ route('login') }}">
                Sudah punya Akun?
            </a>
        </div>


        <button type="submit" class="register-button">
            Regis
        </button>

    </form>

    </div>


    <!-- ILUSTRASI KANAN -->
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

function togglePassword(id) {

    const input = document.getElementById(id);

    if (input.type === "password") {
        input.type = "text";
    } else {
        input.type = "password";
    }

}

</script>

</body>
</html>