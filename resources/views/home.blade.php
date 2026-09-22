<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TechStore</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    <header class="navbar">
        <div class="container nav-content">

            <a href="{{ route('home') }}" class="brand">
                <div class="logo-icon">
                    <div class="laptop-icon"></div>
                </div>

                <span>TechStore</span>
            </a>

            <nav>
                <a href="/">Home</a>
                <a href="#">Products</a>
                <a href="#">Article</a>
                <a href="#">About</a>
            </nav>

        </div>
    </header>


    <main>

        <section class="hero">

            <div class="circle circle-top"></div>
            <div class="circle circle-middle"></div>
            <div class="circle circle-bottom"></div>

            <div class="container hero-content">

                <div class="hero-text">

                    <h1>
                        PERFORMANCE<br>
                        ULTRABOOK
                    </h1>

                    <p>
                        Laptop solutions for every budget and need!
                    </p>

                    <a href="{{ route('login') }}" class="buy-button">
                        Buy Now
                    </a>

                </div>


                <div class="hero-image">

                    <img
                        src="{{ asset('images/laptop.png') }}"
                        alt="Laptop TechStore"
                    >

                </div>

            </div>

        </section>

    </main>

</body>
</html>