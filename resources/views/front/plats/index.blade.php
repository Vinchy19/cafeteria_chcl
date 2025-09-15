{{-- index pour le plat --}}
@extends('layouts.sidebar')

@section('title', 'Dashboard - Cafétéria CHCL')

<style>
    .active-plats{
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
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .modal-container {
        animation: modalFadeIn 0.3s ease-out;
    }
</style>

@section('content')
    @if(session('success'))
        <div
            class="mb-4 rounded-lg bg-green-100 border border-green-300 text-green-800 px-4 py-3 text-sm font-medium shadow-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div
            class="mb-4 rounded-lg bg-red-100 border border-red-300 text-red-800 px-4 py-3 text-sm font-medium shadow-sm">
            {{ session('error') }}
        </div>
    @endif
    <div class="container mx-auto px-4 py-8">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-0 bg-white p-4 rounded-xl shadow-md">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                    <span class="text-blue-700">🍽️</span>
                    <span class="text-blue-800">Liste des plats</span>
                </h1>
                <p class="mt-2 text-base text-gray-500">Gérez, ajoutez ou modifiez vos plats facilement dans le menu</p>
            </div>

            <button onclick="openModal()"
                    class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-5 py-2.5 rounded-lg shadow-lg transition transform hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="font-semibold">Ajouter un plat</span>
            </button>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-lg shadow-md overflow-hidden mt-5" >
            <table class="min-w-full table-auto border-collapse">
                <thead class="bg-blue-100">
                <tr>
                    <th class="px-6 py-3 text-left text-gray-700 font-semibold">ID</th>
                    <th class="px-6 py-3 text-left text-gray-700 font-semibold">Nom</th>
                    <th class="px-6 py-3 text-left text-gray-700 font-semibold">Cuisson</th>
                    <th class="px-6 py-3 text-left text-gray-700 font-semibold">Prix</th>
                    <th class="px-6 py-3 text-left text-gray-700 font-semibold">Quantité Disp.</th>
                    <th class="px-6 py-3 text-center text-gray-700 font-semibold">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($plats as $plat)
                    <tr class="border-b hover:bg-blue-50 transition">
                        <td class="px-6 py-4">{{ $plat->id }}</td>
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $plat->nom_plat }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $plat->cuisson_plat }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ number_format($plat->prix_plat, 2) }} HTG</td>
                        <td class="px-6 py-4 text-gray-700">{{ $plat->quantite_plat }}</td>
                        <td class="px-6 py-4 flex justify-center gap-2">
                            {{-- Bouton Voir --}}
                            <button type="button"
                                    onclick="openShowModal({{ $plat->id }}, '{{ $plat->nom_plat }}', '{{ $plat->cuisson_plat }}', '{{ $plat->prix_plat }}', '{{ $plat->quantite_plat }}')"
                                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md text-sm shadow transition-colors">
                                <i class="fas fa-eye mr-1"></i> Voir
                            </button>

                            {{-- Bouton Modifier --}}
                            <button type="button"
                                    onclick="openEditModal({{ $plat->id }}, '{{ $plat->nom_plat }}', '{{ $plat->cuisson_plat }}', '{{ $plat->prix_plat }}', '{{ $plat->quantite_plat }}')"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md text-sm shadow transition-colors">
                                <i class="fas fa-edit mr-1"></i> Modifier
                            </button>

                            {{-- Bouton Supprimer --}}
                            <form class="p-0 m-0" action="{{ route('plats.destroy', $plat->id) }}" method="POST"
                                  onsubmit="return confirm('Voulez-vous vraiment supprimer ce plat ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm shadow transition-colors">
                                    <i class="fas fa-trash mr-1"></i> Supprimer
                                </button>
                            </form>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Aucun plat enregistré.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal d'ajout de plat -->
    <div id="modal-overlay" class="modal-overlay">
        <div class="modal-container">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 mx-auto">
                <!-- Header -->
                <div class="flex justify-between items-center border-b pb-3 mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Ajouter un nouveau plat</h2>
                    <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                </div>

                <!-- Formulaire -->
                <form method="POST" action="{{ route('plats.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom du plat *</label>
                        <input type="text" name="nom_plat" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 border">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type de cuisson *</label>
                        <select name="cuisson_plat" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 border">
                            <option value="">Sélectionnez une option</option>
                            <option value="cru">Cru</option>
                            <option value="cuit">Cuit</option>
                            <option value="grille">Grillé</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Prix (HTG) *</label>
                        <input type="number" name="prix_plat" step="0.01" min="0" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 border">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantité disponible *</label>
                        <input type="number" name="quantite_plat" min="0" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 border">
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
    <!-- Modal Voir -->
    <div id="show-modal-overlay" class="modal-overlay">
        <div class="modal-container">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 mx-auto">
                <!-- Header -->
                <div class="flex justify-between items-center border-b border-gray-200 pb-4 mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                        <span class="text-blue-600">📋</span>
                        Détails du plat
                    </h2>
                    <button onclick="closeShowModal()" class="text-gray-500 hover:text-gray-700 text-2xl transition-colors duration-200">
                        &times;
                    </button>
                </div>

                <!-- Contenu -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3 py-2">
                    <span class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                        🍽️
                    </span>
                        <div>
                            <p class="text-sm text-gray-600">Nom</p>
                            <p id="show-nom" class="font-semibold text-gray-900"></p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 py-2">
                    <span class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center text-orange-600">
                        🔥
                    </span>
                        <div>
                            <p class="text-sm text-gray-600">Cuisson</p>
                            <p id="show-cuisson" class="font-semibold text-gray-900"></p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 py-2">
                    <span class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                        💰
                    </span>
                        <div>
                            <p class="text-sm text-gray-600">Prix</p>
                            <p id="show-prix" class="font-semibold text-gray-900"></p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 py-2">
                    <span class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center text-purple-600">
                        📦
                    </span>
                        <div>
                            <p class="text-sm text-gray-600">Quantité disponible</p>
                            <p id="show-quantite" class="font-semibold text-gray-900"></p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex justify-end mt-8 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeShowModal()"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Modifier -->
    <div id="edit-modal-overlay" class="modal-overlay">
        <div class="modal-container">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 mx-auto">
                <div class="flex justify-between items-center border-b pb-3 mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Modifier le plat</h2>
                    <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                </div>

                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Nom *</label>
                        <input type="text" name="nom_plat" id="edit-nom" required
                               class="w-full border-gray-300 rounded-md shadow-sm p-2">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Cuisson *</label>
                        <select name="cuisson_plat" id="edit-cuisson" required
                                class="w-full border-gray-300 rounded-md shadow-sm p-2">
                            <option value="cru">Cru</option>
                            <option value="cuit">Cuit</option>
                            <option value="grille">Grillé</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Prix *</label>
                        <input type="number" name="prix_plat" id="edit-prix" step="0.01" required
                               class="w-full border-gray-300 rounded-md shadow-sm p-2">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Quantité *</label>
                        <input type="number" name="quantite_plat" id="edit-quantite" required
                               class="w-full border-gray-300 rounded-md shadow-sm p-2">
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t mt-6">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-200 rounded-lg">Annuler</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Fonctions pour ouvrir et fermer le modal
        function openModal() {
            document.getElementById('modal-overlay').classList.add('active');
        }

        function closeModal() {
            document.getElementById('modal-overlay').classList.remove('active');
        }

        // Fermer le modal en cliquant à l'extérieur
        document.getElementById('modal-overlay').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Fermer le modal avec la touche Échap
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });

        // === Modal Voir ===
        function openShowModal(id, nom, cuisson, prix, quantite) {
            document.getElementById('show-nom').innerText = nom;
            document.getElementById('show-cuisson').innerText = cuisson;
            document.getElementById('show-prix').innerText = prix;
            document.getElementById('show-quantite').innerText = quantite;
            document.getElementById('show-modal-overlay').classList.add('active');
        }
        function closeShowModal() {
            document.getElementById('show-modal-overlay').classList.remove('active');
        }

        // === Modal Modifier ===
        function openEditModal(id, nom, cuisson, prix, quantite) {
            let form = document.getElementById('editForm');
            form.action = "{{ route('plats.update', ':id') }}".replace(':id', id);

            document.getElementById('edit-nom').value = nom;
            document.getElementById('edit-cuisson').value = cuisson;
            document.getElementById('edit-prix').value = prix;
            document.getElementById('edit-quantite').value = quantite;

            document.getElementById('edit-modal-overlay').classList.add('active');
        }
        function closeEditModal() {
            document.getElementById('edit-modal-overlay').classList.remove('active');
        }


    </script>
@endpush
@endsection
