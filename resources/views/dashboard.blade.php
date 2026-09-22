<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>TechStore</title>

    <link
        rel="stylesheet"
        href="{{ asset('css/dashboard.css') }}"
    >

</head>

<body>


<!-- =========================
     HEADER
========================= -->

<header class="header">

    <!-- LOGO -->

    <a href="{{ route('dashboard') }}" class="brand">

        <div class="brand-icon">
            <div class="small-laptop"></div>
        </div>

        <span>TechStore</span>

    </a>


    <!-- SEARCH -->

    <form class="search-form">

        <input
            type="text"
            placeholder="Cari laptop..."
        >

        <button type="submit">
            Search
        </button>

    </form>


    <!-- USER -->

    <div class="user-area">

        <div class="avatar">

            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}

        </div>


        <form
            action="{{ route('logout') }}"
            method="POST"
        >

            @csrf

            <button
                type="submit"
                class="logout"
            >
                Keluar
            </button>

        </form>

    </div>

</header>


<!-- =========================
     NAVIGATION
========================= -->

<nav class="navbar">

    <a href="{{ route('dashboard') }}">
        Home
    </a>

</nav>



<!-- =========================
     HERO
========================= -->

<main>

<section class="hero">


    <!-- BACKGROUND -->

    <div class="circle circle-one"></div>

    <div class="circle circle-two"></div>

    <div class="circle circle-three"></div>



    <div class="hero-content">


        <!-- TEXT -->

        <div class="hero-text">

            <h1>
                PERFORMANCE
                <br>
                ULTRABOOK
            </h1>

            <p>
                "Laptop solutions for every budget and need!"
            </p>


            <a href="#" class="buy-button">
                Buy Now
            </a>

        </div>



        <!-- LAPTOP -->

        <div class="hero-image">

            <img
                src="{{ asset('images/laptop.png') }}"
                alt="Laptop"
            >

        </div>


    </div>

</section>



<!-- =========================
     FLASH SALE
========================= -->

<section class="flash-sale">

    <h2>Flash Sale</h2>

</section>


</main>


</body>
</html>