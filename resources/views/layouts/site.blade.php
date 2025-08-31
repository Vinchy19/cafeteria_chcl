<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'La Cafétéria du CHCL')</title>

    <!-- CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Indie+Flower&family=Nanum+Myeongjo&display=swap"
          rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
<!-- Navigation -->
<section class="header">
    <nav>
        <ul>
            <h1>
                <i class="fa-solid fa-mug-hot"></i>
                <a href="{{ route('accueil') }}">La Cafétéria du CHCL</a>
            </h1>
            <div class="lien">
                @guest
                    <li><a class="btn" href="{{ route('login') }}">Login</a></li>
                    <li><a class="btn" href="{{ route('register') }}">Sign Up</a></li>
                @else

                    <li><a class="btn" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn">Logout</button>
                        </form>
                    </li>
                @endguest
            </div>
        </ul>
    </nav>
</section>

<!-- Contenu spécifique -->
<main>
    @yield('content')
</main>

<!-- Footer -->
<footer class="pied">
    <p>&copy; Phoenix 2024 | Tous droits réservés</p>
</footer>

<!-- JS -->
<script src="https://kit.fontawesome.com/f546fde413.js" crossorigin="anonymous"></script>
<script src="{{ asset('js/script.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
