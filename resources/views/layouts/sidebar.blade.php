<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - Cafétéria CHCL')</title>

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'prime': '#3B82F6',
                        'su': '#10B981',
                        'da': '#8B5CF6',
                        'war': '#F59E0B',
                    }
                }
            }
        }
    </script>

    <!-- Polices et icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #fffbed
        }

        .main-content {
            margin-left: 16rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .main-content {
                margin-left: 0;
            }
        }

        @keyframes background-shine {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        @keyframes text-shine {
            0% {
                background-position: -100% 0;
            }
            100% {
                background-position: 200% 0;
            }
        }

        .animated-background {
            background: linear-gradient(
                270deg,
                #fffbed,
                #bed9ef,
                #bdd6f6,
                #fffbed);
            background-size: 200% 200%;
            animation: background-shine 8s ease infinite;
        }

        .shining-text {
            background: linear-gradient(
                90deg,
                #3b82f6,
                #8b5cf6,
                #cf68c8,
                #8b5cf6,
                #3b82f6
            );
            background-size: 200% auto;
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: text-shine 3s linear infinite;
        }

        .border-gradient {
            border-bottom: 3px solid;
            border-image: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899) 1;
        }

    </style>

    <!-- Styles supplémentaires spécifiques aux pages -->
    @stack('styles')
</head>
<body class="bg-gray-100">
<div class="flex">
    <!-- Sidebar -->
    <nav class="sidebar w-64 shadow-md" >
        <div class="p-4">
            <div class="sidebar-header text-center py-3">
                <h4 class="font-bold text-xl text-gray-800">CAFETERIA</h4>
                <p class="text-gray-500 text-sm">Tableau de bord de l'administration</p>
            </div>
            <ul class="mt-6 space-y-2">
                <li class="active-dashboard">
                    <a class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg"
                       href="{{ route('dashboard') }}">
                        <i class="fas fa-home w-5"></i>
                        <span class="ml-3">Dashboard</span>
                    </a>
                </li>
                <li class="active-plats">
                    <a class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg"
                       href="{{ route('plats.index') }}">
                        <i class="fas fa-utensils w-5"></i>
                        <span class="ml-3">Plats</span>
                    </a>
                </li>
                <li class="active-clients">
                    <a class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg"
                       href="{{ route('clients.index') }}">
                        <i class="fas fa-users w-5"></i>
                        <span class="ml-3">Clients</span>
                    </a>
                </li>
                <li class="active-ventes">
                    <a class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg"
                       href="{{ route('ventes.index') }}">
                        <i class="fas fa-shopping-cart w-5"></i>
                        <span class="ml-3">Ventes</span>
                    </a>
                </li>
                @if(Auth::user()->role == 'admin')
                    <li class="active-users">
                        <a class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg" href="#">
                            <i class="fas fa-user-tie w-5"></i>
                            <span class="ml-3">Utilisateurs</span>
                        </a>
                    </li>
                    <li class="active-password">
                        <a class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg"
                           href="{{ route('profile.edit') }}">
                            <i class="fa-solid fa-key w-5"></i>
                            <span class="ml-3">Change Password</span>
                        </a>
                    </li>
                @endif
                <li class="active-logout">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="flex items-center w-full px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-sign-out-alt w-5"></i>
                            <span class="ml-3">Se déconnecter</span>
                        </button>
                    </form>
                </li>
            </ul>

            <div class="mt-8 pt-4 border-t border-gray-200">
                <div class="flex items-center px-4 py-2 text-gray-700">
                    <i class="fas fa-user-circle mr-3"></i>
                    <div>
                        <a href="{{ route('profile.edit') }}">
                            <p class="font-medium">{{ Auth::user()->name }}</p>
                        </a>
                        <p class="text-sm text-gray-500">Droit: {{ Auth::user()->role }}</p>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content flex-1 p-6">
        <div class="animated-background py-5 mb-8 rounded-xl shadow-md border-gradient">
            <h1 class="text-4xl font-bold text-center shining-text">
                CAFETERIA DU CHCL
            </h1>
        </div>

        <!-- Contenu spécifique à chaque page -->
        @yield('content')
    </main>
</div>



{{--<!-- Scripts supplémentaires spécifiques aux pages -->--}}
@stack('scripts')
</body>
</html>
