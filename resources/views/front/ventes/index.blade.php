@extends('layouts.sidebar')

@section('title', 'Ventes - Cafétéria CHCL')

<style>
    .active-ventes {
        background-color: #dbeafe;
        border-radius: 10px;
    }

    /* Style pour le modal */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-container {
        background-color: white;
        border-radius: 0.5rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
    }

    /* Animation pour le modal */
    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-container {
        animation: modalFadeIn 0.3s ease-out;
    }
</style>

@section('content')
    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-100 border border-green-300 text-green-800 px-4 py-3 text-sm font-medium shadow-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-100 border border-red-300 text-red-800 px-4 py-3 text-sm font-medium shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="container mx-auto px-4 py-8">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-6 bg-white p-6 rounded-xl shadow-md">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                    <span class="text-blue-700">🛒</span>
                    <span class="text-blue-800">Gestion des ventes</span>
                </h1>
                <p class="mt-2 text-base text-gray-500">Consultez et enregistrez les ventes de la cafétéria</p>
            </div>

            <button onclick="openModal()"
                    class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-5 py-2.5 rounded-lg shadow-lg transition transform hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="font-semibold">Nouvelle vente</span>
            </button>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-blue-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Plat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Quantité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Prix Unitaire</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Vendu par</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($ventes as $vente)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $vente->id }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $vente->client->nom_client }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">{{ $vente->plat->nom_plat }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">{{ $vente->nbre_plat }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($vente->plat->prix_plat, 2) }} HTG</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                {{ number_format($vente->plat->prix_plat * $vente->nbre_plat, 2) }} HTG
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">{{ $vente->date_vente }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">{{ $vente->user->name }}</td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center justify-center space-x-2">
                                    {{-- Bouton Modifier --}}
                                    <button type="button"
                                            onclick="openEditModal({{ $vente->id }}, '{{ $vente->client_id }}', '{{ $vente->plat_id }}', '{{ $vente->nbre_plat }}')"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md text-sm shadow transition-colors flex items-center"
                                            title="Modifier">
                                        <i class="fas fa-edit mr-1"></i>
                                    </button>

                                    {{-- Bouton Supprimer --}}
                                    <form action="{{ route('ventes.destroy', $vente->id) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette vente ?')"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm shadow transition-colors flex items-center"
                                                title="Supprimer">
                                            <i class="fas fa-trash mr-1"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-gray-500">Aucune vente enregistrée.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal d'ajout de vente -->
    <div id="modal-overlay" class="modal-overlay">
        <div class="modal-container">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 mx-auto">
                <!-- Header -->
                <div class="flex justify-between items-center border-b pb-3 mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Enregistrer une nouvelle vente</h2>
                    <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                </div>

                <!-- Formulaire -->
                <form method="POST" action="{{ route('ventes.store') }}">
                    @csrf

                    <!-- Champ caché pour l'utilisateur connecté -->
                    <input type="hidden" name="user_id" value="{{ Auth::id() }}">

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Client *</label>
                        <select name="client_id" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 border">
                            <option value="">Sélectionnez un client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->nom_client }} ({{ $client->type_client }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Plat *</label>
                        <select name="plat_id" id="plat_id" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 border">
                            <option value="">Sélectionnez un plat</option>
                            @foreach($plats as $plat)
                                <option value="{{ $plat->id }}" data-prix="{{ $plat->prix_plat }}">
                                    {{ $plat->nom_plat }} - {{ number_format($plat->prix_plat, 2) }} HTG
                                    @if($plat->quantite_plat > 0)
                                        ({{ $plat->quantite_plat }} disponible(s))
                                    @else
                                        (Rupture de stock)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantité *</label>
                        <input type="number" name="nbre_plat" id="nbre_plat" min="1" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 border">
                    </div>

                        <input type="hidden" name="date_vente" value="{{ now()->format('Y-m-d') }}" >


                    <div class="mb-4 p-3 bg-blue-50 rounded-lg">
                        <p class="text-sm text-blue-700 font-medium">Total: <span id="total-vente">0.00</span> HTG</p>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end space-x-3 pt-4 border-t mt-6">
                        <button type="button" onclick="closeModal()"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            Annuler
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal d'édition -->
    <div id="edit-modal-overlay" class="modal-overlay">
        <div class="modal-container">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 mx-auto">
                <!-- Header -->
                <div class="flex justify-between items-center border-b pb-3 mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Modifier la vente</h2>
                    <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                </div>

                <!-- Formulaire -->
                <form id="editVenteForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Client *</label>
                        <select name="client_id" id="edit_client_id" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 border">
                            <option value="">Sélectionnez un client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->nom_client }} ({{ $client->type_client }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Plat *</label>
                        <select name="plat_id" id="edit_plat_id" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 border">
                            <option value="">Sélectionnez un plat</option>
                            @foreach($plats as $plat)
                                <option value="{{ $plat->id }}">{{ $plat->nom_plat }} - {{ number_format($plat->prix_plat, 2) }} HTG</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantité *</label>
                        <input type="number" name="nbre_plat" id="edit_nbre_plat" min="1" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 border">
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end space-x-3 pt-4 border-t mt-6">
                        <button type="button" onclick="closeEditModal()"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            Annuler
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Fonctions pour le modal d'ajout
        function openModal() {
            document.getElementById('modal-overlay').classList.add('active');
        }

        function closeModal() {
            document.getElementById('modal-overlay').classList.remove('active');
        }

        // Fonctions pour le modal d'édition
        function openEditModal(id, client_id, plat_id, nbre_plat) {
            // Remplir le formulaire
            document.getElementById('edit_client_id').value = client_id;
            document.getElementById('edit_plat_id').value = plat_id;
            document.getElementById('edit_nbre_plat').value = nbre_plat;

            // Changer l'action du formulaire
            const form = document.getElementById("editVenteForm");
            form.action = "{{ route('ventes.update', ':id') }}".replace(':id', id);

            // Ouvrir le modal
            document.getElementById('edit-modal-overlay').classList.add('active');
        }

        function closeEditModal() {
            document.getElementById('edit-modal-overlay').classList.remove('active');
        }

        // Fermer les modals en cliquant à l'extérieur
        document.getElementById('modal-overlay')?.addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        document.getElementById('edit-modal-overlay')?.addEventListener('click', function(e) {
            if (e.target === this) closeEditModal();
        });

        // Fermer les modals avec la touche Échap
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
                closeEditModal();
            }
        });

        // Calcul du total pour le modal d'ajout
        function calculerTotal() {
            const platSelect = document.getElementById('plat_id');
            const quantiteInput = document.getElementById('nbre_plat');
            const totalElement = document.getElementById('total-vente');

            if (platSelect.selectedIndex > 0 && quantiteInput.value > 0) {
                const prix = parseFloat(platSelect.options[platSelect.selectedIndex].dataset.prix);
                const quantite = parseInt(quantiteInput.value);
                const total = prix * quantite;
                totalElement.textContent = total.toFixed(2);
            } else {
                totalElement.textContent = '0.00';
            }
        }

        // Écouter les changements pour le calcul du total
        document.getElementById('plat_id')?.addEventListener('change', calculerTotal);
        document.getElementById('nbre_plat')?.addEventListener('input', calculerTotal);

        // Initialiser le calcul au chargement
        document.addEventListener('DOMContentLoaded', function() {
            calculerTotal();
        });
    </script>
@endpush
