<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'La Cafétéria du CHCL')</title>

    <!-- Polices et icônes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Indie+Flower&family=Nanum+Myeongjo&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Styles Tailwind CSS intégrés via Laravel -->
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'indie': ['Indie Flower', 'cursive'],
                        'nanum': ['Nanum Myeongjo', 'serif'],
                    },
                    colors: {
                        'cafe-brown': '#8B4513',
                        'cafe-cream': '#F5F5DC',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen font-sans">

<header class="bg-amber-700 text-white shadow-md">
    <nav class="container mx-auto px-4 py-3">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0">

            <!-- Logo / Titre -->
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold flex items-center">
                    <i class="fa-solid fa-mug-hot mr-2"></i>
                    <a href="{{ route('accueil') }}" class="hover:text-amber-200 transition-colors duration-200">
                        La Cafétéria du CHCL
                    </a>
                </h1>

                <!-- Bouton Hamburger (mobile uniquement) -->
                <button id="menu-toggle" class="md:hidden text-white text-2xl focus:outline-none">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>

            <!-- Menu -->
            <div id="menu" class="flex flex-col md:flex-row flex-wrap gap-2 hidden md:flex">
                @guest
                    <a class="bg-amber-600 hover:bg-amber-500 text-white px-4 py-2 rounded-md transition-colors duration-200 shadow-sm"
                       href="{{ route('login') }}">Login</a>
                    <a class="bg-amber-800 hover:bg-amber-700 text-white px-4 py-2 rounded-md transition-colors duration-200 shadow-sm"
                       href="{{ route('register') }}">Sign Up</a>
                @else
                    <a class="bg-amber-600 hover:bg-amber-500 text-white px-4 py-2 rounded-md transition-colors duration-200 shadow-sm"
                       href="{{ route('dashboard') }}">Dashboard</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                                class="bg-amber-800 hover:bg-amber-700 text-white px-4 py-2 rounded-md transition-colors duration-200 shadow-sm">
                            Logout
                        </button>
                    </form>
                @endguest
            </div>
        </div>
    </nav>
</header>

<!-- Contenu spécifique -->
<main class="flex-grow container mx-auto px-4 py-8">
    @yield('content')
</main>

<!-- Footer -->
<footer class="bg-amber-700 text-white py-6 text-center mt-auto">
    <p>&copy; Phoenix 2024 | Tous droits réservés</p>
</footer>

<!-- JS -->
<script src="https://kit.fontawesome.com/f546fde413.js" crossorigin="anonymous"></script>
<script>
    // Toggle du menu mobile
    document.getElementById("menu-toggle").addEventListener("click", function () {
        document.getElementById("menu").classList.toggle("hidden");
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>

</html>

