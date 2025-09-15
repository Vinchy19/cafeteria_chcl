@extends('layouts.sidebar')

@section('title', 'Dashboard - Cafétéria CHCL')

<style>
    .active-clients {
        background-color: #dbeafe;
        border-radius: 10px;
    }

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
        animation: modalFadeIn 0.3s ease-out;
    }

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
        <div class="flex justify-between items-center mb-0 bg-white p-4 rounded-xl shadow-md">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                    <span class="text-blue-700">👥</span>
                    <span class="text-blue-800">Liste des clients</span>
                </h1>
                <p class="mt-2 text-base text-gray-500">Gérez, ajoutez ou modifiez vos clients facilement</p>
            </div>

            <button onclick="openAddModal()"
                    class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-5 py-2.5 rounded-lg shadow-lg transition transform hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="font-semibold">Ajouter un client</span>
            </button>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mt-5">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-blue-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Téléphone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Ajouté par</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($clients as $client)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $client->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $client->nom_client }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($client->type_client) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $client->phone_client ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $client->created_by ?? 'Non spécifié' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-3">
                                    {{-- Bouton Modifier --}}
                                    <button onclick="openEditModal({{ $client->id }}, '{{ addslashes($client->nom_client) }}', '{{ $client->type_client }}', '{{ $client->phone_client }}')"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md text-sm shadow transition-colors flex items-center"
                                            title="Modifier le client">
                                        <i class="fas fa-edit mr-1"></i> Modifier
                                    </button>

                                    {{-- Bouton Supprimer --}}
                                    <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?')"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm shadow transition-colors flex items-center"
                                                title="Supprimer le client">
                                            <i class="fas fa-trash mr-1"></i> Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Aucun client trouvé.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal d'ajout -->
    <div id="add-modal" class="modal-overlay">
        <div class="modal-container">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 mx-auto">
                <!-- Header -->
                <div class="flex justify-between items-center border-b pb-3 mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Ajouter un client</h2>
                    <button onclick="closeAddModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                </div>

                <!-- Formulaire -->
                <form method="POST" action="{{ route('clients.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                        <input type="text" name="nom_client" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 border">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                        <select name="type_client" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 border">
                            <option value="">Sélectionnez un type</option>
                            <option value="etudiant">Étudiant</option>
                            <option value="professeur">Professeur</option>
                            <option value="personnel admin">Personnel Administratif</option>
                            <option value="invite">Invité</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                        <input type="tel" name="phone_client"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 border">
                    </div>

                    <input type="hidden" name="created_by" value="{{Auth::user()->name}}">

                    <!-- Footer -->
                    <div class="flex justify-end space-x-3 pt-4 border-t mt-6">
                        <button type="button" onclick="closeAddModal()"
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
    <div id="edit-modal" class="modal-overlay">
        <div class="modal-container">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 mx-auto">
                <!-- Header -->
                <div class="flex justify-between items-center border-b pb-3 mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Modifier le client</h2>
                    <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                </div>

                <!-- Formulaire -->
                <form id="editForm" method="POST" data-base-url="{{ route('clients.update', ':id') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                        <input type="text" id="edit_nom" name="nom_client" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 border">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                        <select id="edit_type" name="type_client" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 border">
                            <option value="etudiant">Étudiant</option>
                            <option value="professeur">Professeur</option>
                            <option value="personnel admin">Personnel Administratif</option>
                            <option value="invite">Invité</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                        <input type="tel" id="edit_phone" name="phone_client"
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
                            Modifier
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
        function openAddModal() {
            document.getElementById('add-modal').classList.add('active');
        }

        function closeAddModal() {
            document.getElementById('add-modal').classList.remove('active');
        }

        // Fonctions pour le modal d'édition
        function openEditModal(id, nom, type, phone) {
            const form = document.getElementById('editForm');
            const baseUrl = form.getAttribute('data-base-url');
            form.action = baseUrl.replace(':id', id);

            document.getElementById('edit_nom').value = nom;
            document.getElementById('edit_type').value = type;
            document.getElementById('edit_phone').value = phone || '';

            document.getElementById('edit-modal').classList.add('active');
        }

        function closeEditModal() {
            document.getElementById('edit-modal').classList.remove('active');
        }

        // Fermer les modals en cliquant à l'extérieur
        document.querySelectorAll('.modal-overlay').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    if (this.id === 'add-modal') closeAddModal();
                    if (this.id === 'edit-modal') closeEditModal();
                }
            });
        });

        // Fermer avec la touche Échap
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAddModal();
                closeEditModal();
            }
        });
    </script>
@endpush
