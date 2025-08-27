@extends('layouts.site')

@section('title', 'Dashboard - Cafétéria CHCL')

@push('styles')
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block sidebar shadow-sm">
                <div class="position-sticky">
                    <div class="sidebar-header text-center py-3">
                        <h4 class="cafeteria-title">Cafeteria</h4>
                        <p class="text-muted">Tableau de bord de l'administration</p>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">
                                <i class="fas fa-home"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('plats.index') }}">
                                <i class="fas fa-utensils"></i> Plats
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('clients.index') }}">
                                <i class="fas fa-users"></i> Clients
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('ventes.index') }}">
                                <i class="fas fa-shopping-cart"></i> Ventes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-user-tie"></i> Utilisateurs
                            </a>
                        </li>
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="fa-solid fa-key"></i> Change Password
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i> Se deconnecter
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>

                        <div class="user-connect-s">
                            <li>
                                <i class="fas fa-user-circle"></i>
                                 {{ auth()->user()->name }}
                            </li>
                            <li style="padding-left: 40px;">
                                Droit : {{ auth()->user()->role }}
                            </li>
                        </div>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="sidebar-header text-center py-3 d-flex justify-content-center flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">CAFETERIA DU CHCL</h1>
                </div>

                <!-- Stat Cards -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="card text-white bg-prime mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Ventes aujourd.</h5>
                                <p class="card-text">#</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card text-white bg-su mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Clients</h5>
                                <p class="card-text">#</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card text-white bg-da mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Users</h5>
                                <p class="card-text">#</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card text-white bg-war mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Revenue Ajourd.</h5>
                                <p class="card-text"># HTG</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts -->
                <div class="row mt-4 cercle">
                    <div class="col-md-3">
                        <canvas id="totalOrdersChart"></canvas>
                        <p class="text-center mt-2">Users per Classes</p>
                    </div>

                    <div class="col-md-3">
                        <canvas id="customerGrowthChart"></canvas>
                        <p class="text-center mt-2">Clients per Type</p>
                    </div>

                    <div class="col-md-3">
                        <canvas id="totalRevenueChart"></canvas>
                        <p class="text-center mt-2">Clients et Vente aujourd.</p>
                    </div>
                </div>

                <!-- Modal pour le rapport PDF -->
                <div id="myModal" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Généré un rapport</h2>
                            <span class="close">&times;</span>
                        </div>
                        <div class="modal-body">
                            <form action="#" method="post" class="contact-form">
                                @csrf
                                <label for="date_deb">Date debut</label>
                                <input type="date" name="date_deb" id="date_deb" required />
                                <label for="date_fin">Date fin</label>
                                <input type="date" name="date_fin" id="date_fin" required/>

                                <input type="submit" class="btn btn-orange my-20" value="Validé">
                            </form>
                        </div>
                    </div>
                </div>

                <div class="genere">
                    <button id="myBtn" class="btn btn-orange my-10">
                        <i class="fa-solid fa-file-pdf"></i> Généré un rapport
                    </button>
                </div>
            </main>
        </div>
    </div>
@endsection
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

{{--@push('scripts')--}}
    <!-- Bootstrap JS Bundle -->
    <!-- Chart.js -->
    <!-- Custom Script -->
{{--    <script>--}}
{{--        // Données pour les graphiques--}}
{{--        const rolesData = @json($rolesCounts);--}}
{{--        const roleData = @json($typeClientsCounts);--}}
{{--        const ventesAujourdhui = {{ $ventesAujourdhui }};--}}
{{--        const totalClients = {{ $totalClients }};--}}

{{--        // Scripts pour initialiser les graphiques--}}
{{--        document.addEventListener('DOMContentLoaded', function() {--}}
{{--            // Graphique 1: Utilisateurs par rôle--}}
{{--            const ctx1 = document.getElementById('totalOrdersChart').getContext('2d');--}}
{{--            new Chart(ctx1, {--}}
{{--                type: 'doughnut',--}}
{{--                data: {--}}
{{--                    labels: Object.keys(rolesData),--}}
{{--                    datasets: [{--}}
{{--                        data: Object.values(rolesData),--}}
{{--                        backgroundColor: [--}}
{{--                            '#FF6384',--}}
{{--                            '#36A2EB',--}}
{{--                            '#FFCE56'--}}
{{--                        ]--}}
{{--                    }]--}}
{{--                }--}}
{{--            });--}}

{{--            // Graphique 2: Clients par type--}}
{{--            const ctx2 = document.getElementById('customerGrowthChart').getContext('2d');--}}
{{--            new Chart(ctx2, {--}}
{{--                type: 'pie',--}}
{{--                data: {--}}
{{--                    labels: Object.keys(roleData),--}}
{{--                    datasets: [{--}}
{{--                        data: Object.values(roleData),--}}
{{--                        backgroundColor: [--}}
{{--                            '#4BC0C0',--}}
{{--                            '#9966FF',--}}
{{--                            '#FF9F40',--}}
{{--                            '#8AC249'--}}
{{--                        ]--}}
{{--                    }]--}}
{{--                }--}}
{{--            });--}}

{{--            // Graphique 3: Ventes vs Clients--}}
{{--            const ctx3 = document.getElementById('totalRevenueChart').getContext('2d');--}}
{{--            new Chart(ctx3, {--}}
{{--                type: 'bar',--}}
{{--                data: {--}}
{{--                    labels: ['Ventes aujourd\'hui', 'Total Clients'],--}}
{{--                    datasets: [{--}}
{{--                        label: 'Statistiques',--}}
{{--                        data: [ventesAujourdhui, totalClients],--}}
{{--                        backgroundColor: [--}}
{{--                            '#3e95cd',--}}
{{--                            '#8e5ea2'--}}
{{--                        ]--}}
{{--                    }]--}}
{{--                }--}}
{{--            });--}}

{{--            // Gestion de la modal--}}
{{--            const modal = document.getElementById("myModal");--}}
{{--            const btn = document.getElementById("myBtn");--}}
{{--            const span = document.getElementsByClassName("close")[0];--}}

{{--            btn.onclick = function() {--}}
{{--                modal.style.display = "block";--}}
{{--            }--}}

{{--            span.onclick = function() {--}}
{{--                modal.style.display = "none";--}}
{{--            }--}}

{{--            window.onclick = function(event) {--}}
{{--                if (event.target == modal) {--}}
{{--                    modal.style.display = "none";--}}
{{--                }--}}
{{--            }--}}
{{--        });--}}
{{--    </script>--}}
{{--@endpush--}}
{{--@extends('layouts.app')--}}

{{--@section('content')--}}
{{--    <div class="container">--}}
{{--        <div class="row justify-content-center">--}}
{{--            <div class="col-md-8">--}}
{{--                <div class="card">--}}
{{--                    <div class="card-header">Tableau de Bord Administrateur</div>--}}

{{--                    <div class="card-body">--}}
{{--                        @if (session('status'))--}}
{{--                            <div class="alert alert-success" role="alert">--}}
{{--                                {{ session('status') }}--}}
{{--                            </div>--}}
{{--                        @endif--}}

{{--                        Bienvenue dans l'interface d'administration!--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}
