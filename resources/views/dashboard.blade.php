@extends('layouts.sidebar')

@section('title', 'Dashboard - Cafétéria CHCL')

<style>
    .active-dashboard {
        background-color: #dbeafe;
        border-radius: 10px;
    }

    .chart-container {
        height: 220px;
        margin: 0 auto;
    }

    .chart-wrapper {
        max-width: 320px;
        margin: 0 auto;
    }

    @media (max-width: 768px) {
        .chart-wrapper {
            max-width: 280px;
        }

        .chart-container {
            height: 200px;
        }
    }

    /* Styles pour la modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes slideIn {
        from {
            transform: translateY(-50px) scale(0.9);
            opacity: 0;
        }
        to {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
    }

    .modal-content {
        background-color: white;
        margin: 10% auto;
        padding: 0;
        border-radius: 0.5rem;
        width: 90%;
        max-width: 400px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        animation: slideIn 0.4s ease-out;
        overflow: hidden;
    }

    .modal-header {
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #f8f9fa;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .close {
        color: #aaa;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.3s;
    }

    .close:hover {
        color: #000;
    }

    /* Animation du bouton */
    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
        }
    }

    .pulse-btn {
        animation: pulse 2s infinite;
    }

    .pulse-btn:hover {
        animation: none;
    }

</style>

<!-- Main Content -->
@section('content')
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-prime text-white rounded-lg shadow p-4">
            <h5 class="font-semibold">Total Ventes aujourd.</h5>
            <p class="text-2xl mt-2">
                @if(Auth::User()->role == 'admin')
                    {{$platVenduTotal}}
                @else
                    {{$platVenduUser}}
                @endif
            </p>
        </div>

        <div class="bg-su text-white rounded-lg shadow p-4">
            <h5 class="font-semibold">Total Clients</h5>
            <p class="text-2xl mt-2">{{$nbre_clients}}</p>
        </div>

        @if(Auth::User()->role == 'admin')
            <div class="bg-da text-white rounded-lg shadow p-4">
                <h5 class="font-semibold">Total Users</h5>
                <p class="text-2xl mt-2">
                    {{$nbre_users? : 0}}
                </p>
            </div>
        @endif

        <div class="bg-war text-white rounded-lg shadow p-4">
            <h5 class="font-semibold">Revenue Ajourd.</h5>
            <p class="text-2xl mt-2">
                @if(Auth::User()->role == 'admin')
                    {{$revenuTotal? : 0}} HTG
                @else
                    {{$revenuUser? : 0}} HTG
                @endif
            </p>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="chart-wrapper">
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="chart-container">
                    <canvas id="totalOrdersChart"></canvas>
                </div>
                <p class="mt-2 text-gray-600">Users per Classes</p>
            </div>
        </div>

        <div class="chart-wrapper">
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="chart-container">
                    <canvas id="customerGrowthChart"></canvas>
                </div>
                <p class="mt-2 text-gray-600">Clients per Type</p>
            </div>
        </div>

        <div class="chart-wrapper">
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="chart-container">
                    <canvas id="totalRevenueChart"></canvas>
                </div>
                <p class="mt-2 text-gray-600">Clients et Vente aujourd.</p>
            </div>
        </div>
    </div>

    @if(Auth::user()->role == 'admin')
        <!-- Bouton pour ouvrir la modal -->
        <div class="text-center mt-8">
            <button id="myBtn"
                    class="bg-amber-600 hover:bg-amber-700 text-white font-medium py-3 px-6 rounded-lg inline-flex items-center focus:outline-none focus:ring-4 focus:ring-amber-300 shadow-lg pulse-btn transition duration-300">
                <i class="fa-solid fa-file-pdf mr-3 text-xl"></i> Générer un rapport
            </button>
        </div>

        <!-- Modal pour le rapport PDF -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="text-xl font-bold">Générer un rapport PDF</h2>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <form action="{{ route('ventes.pdf') }}" method="post" class="space-y-4">
                        @csrf
                        <div>
                            <label for="date_deb" class="block text-sm font-medium text-gray-700 mb-1">Date de
                                début</label>
                            <input type="date" name="date_deb" id="date_deb" required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500">
                        </div>
                        <div>
                            <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-1">Date de
                                fin</label>
                            <input type="date" name="date_fin" id="date_fin" required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500">
                        </div>
                        <button type="submit"
                                class="w-full bg-amber-600 hover:bg-amber-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                            Générer le rapport
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection


@push('scripts')
    <script>
        // <!-- Chart.js -->
        document.addEventListener('DOMContentLoaded', function () {
            // Graphique 1: Utilisateurs par rôle
            const ctx1 = document.getElementById('totalOrdersChart').getContext('2d');
            new Chart(ctx1, {
                type: 'doughnut',
                data: {
                    labels: ['Admin', 'Utilisateur', 'Modérateur'],
                    datasets: [{
                        data: [5, 12, 3],
                        backgroundColor: [
                            '#FF6384',
                            '#36A2EB',
                            '#FFCE56'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Graphique 2: Clients par type
            const ctx2 = document.getElementById('customerGrowthChart').getContext('2d');
            new Chart(ctx2, {
                type: 'pie',
                data: {
                    labels: ['Régulier', 'Occasionnel', 'VIP'],
                    datasets: [{
                        data: [45, 25, 10],
                        backgroundColor: [
                            '#4BC0C0',
                            '#9966FF',
                            '#FF9F40'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Graphique 3: Ventes vs Clients
            const ctx3 = document.getElementById('totalRevenueChart').getContext('2d');
            new Chart(ctx3, {
                type: 'bar',
                data: {
                    labels: ['Ventes aujourd\'hui', 'Total Clients'],
                    datasets: [{
                        label: 'Statistiques',
                        data: [28, 80],
                        backgroundColor: [
                            '#3e95cd',
                            '#8e5ea2'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Récupération des éléments
            const modal = document.getElementById("myModal");
            const btn = document.getElementById("myBtn");
            const span = document.getElementsByClassName("close")[0];

            // Vérifier que les éléments existent
            if (btn && modal && span) {
                // Ouvrir la modal
                btn.onclick = function () {
                    modal.style.display = "block";
                    document.body.style.overflow = "hidden";
                }

                // Fermer la modal
                span.onclick = function () {
                    modal.style.display = "none";
                    document.body.style.overflow = "auto";
                }

                // Fermer la modal si on clique en dehors
                window.onclick = function (event) {
                    if (event.target == modal) {
                        modal.style.display = "none";
                        document.body.style.overflow = "auto";
                    }
                }

                // Définir la date du jour comme date maximale
                const today = new Date().toISOString().split('T')[0];
                const dateDeb = document.getElementById("date_deb");
                const dateFin = document.getElementById("date_fin");

                if (dateDeb) dateDeb.setAttribute('max', today);
                if (dateFin) dateFin.setAttribute('max', today);

                // Définir la date de fin par défaut à aujourd'hui
                if (dateFin) dateFin.value = today;

                // Définir la date de début par défaut à il y a 7 jours
                if (dateDeb) {
                    const oneWeekAgo = new Date();
                    oneWeekAgo.setDate(oneWeekAgo.getDate() - 7);
                    dateDeb.value = oneWeekAgo.toISOString().split('T')[0];
                }
            } else {
                console.error("Un ou plusieurs éléments de la modal sont introuvables");
            }
        });
    </script>
    @endpush

