@extends('layouts.sidebar')

@section('title', 'Utilisateurs - Cafétéria CHCL')

<style>
    .active-users {
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

    /* Badge pour les rôles */
    .badge-admin {
        background-color: rgba(10, 131, 15, 0.8);
        color: white;
    }

    .badge-user {
        background-color: #3B82F6;
        color: white;
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
        <div class="flex justify-between items-center mb-6 bg-white p-6 rounded-xl shadow-md">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                    <i data-feather="users" class="mr-2"></i> 👥 Gestion des utilisateurs
                </h1>
                <p class="mt-2 text-base text-gray-500">Administrez les comptes utilisateurs de la cafétéria</p>
            </div>

            <button onclick="openAddModal()"
                    class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-5 py-2.5 rounded-lg shadow-lg transition transform hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="font-semibold">Nouvel utilisateur</span>
            </button>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-blue-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">ID
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nom
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                            Rôle
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Créé
                            le
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $user->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full {{ $user->role === 'admin' ? 'badge-admin' : 'badge-user' }}">
                                    {{ $user->role === 'admin' ? 'Administrateur' : 'Utilisateur' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Bouton Modifier le rôle --}}
                                    <button
                                        onclick="openEditModal('{{ $user->id }}', '{{ addslashes($user->name) }}','{{$user->email}}', '{{ $user->role }}')"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md text-sm shadow transition-colors flex items-center">
                                        <i class="fas fa-edit mr-1"></i> Modifier
                                    </button>

                                    {{-- Bouton Supprimer --}}
                                    @if($user->id !== Auth::id())
                                        {{-- Empêche de se supprimer soi-même --}}
                                        <form class="p-0 m-0" action="{{ route('users.destroy', $user->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="bg-red-500 hover:bg-red-600 text-white p-1 px-3 py-1 rounded-md text-sm shadow transition-colors">
                                                <i class="fas fa-trash mr-1"></i> Supprimer
                                            </button>
                                        </form>
                                    @else
                                        <button type="button"
                                                title="Vous ne pouvez pas vous supprimer"
                                                class="bg-gray-400 text-white p-1 cursor-not-allowed px-3 py-1 rounded-md text-sm shadow transition-colors">
                                            <i class="fas fa-trash mr-1"></i> Supprimer
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Aucun utilisateur trouvé.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal d'ajout d'utilisateur -->
    <div id="add-modal-overlay" class="modal-overlay">
        <div class="modal-container">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 mx-auto">
                <!-- Header -->
                <div class="flex justify-between items-center border-b pb-3 mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Ajouter un nouvel utilisateur</h2>
                    <button onclick="closeAddModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                </div>

                <!-- Formulaire -->
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet *</label>
                        <input type="text" name="name" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 border">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" name="email" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 border">
                        @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe *</label>
                        <input type="password" name="password" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 border">
                        @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirmation du mot de passe
                            *</label>
                        <input type="password" name="password_confirmation" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 border">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rôle *</label>
                        <select name="role" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 border">
                            <option value="">Sélectionnez un rôle</option>
                            <option value="user">Utilisateur</option>
                            <option value="admin">Administrateur</option>
                        </select>
                        @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end space-x-3 pt-4 border-t mt-6">
                        <button type="button" onclick="closeAddModal()"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            Annuler
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Créer l'utilisateur
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de modification -->
    <div id="edit-modal-overlay" class="modal-overlay">
        <div class="modal-container">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 mx-auto">
                <!-- Header -->
                <div class="flex justify-between items-center border-b pb-3 mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Modification de l'utilisateur</h2>
                    <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                </div>

                <!-- Formulaire -->
                <form id="editUserForm" method="POST" action="" data-base-url="{{ route('users.update', 'user_id') }}">
                    @csrf
                    @method('PUT')

                    <!-- Nom -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                        <input type="text" id="editName" name="name" required  readonly
                               class="bg-gray-400 text-white cursor-not-allowed mt-1 block w-full rounded-md shadow-sm p-2 border">
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" id="editEmail" name="email" required
                               class="mt-1 block w-full rounded-md shadow-sm p-2 border">
                    </div>

                    <!-- Mot de passe -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                        <input type="password" name="password"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border">
                    </div>

                    <!-- Confirmation mot de passe -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirmation du mot de passe</label>
                        <input type="password" name="password_confirmation"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border">
                    </div>

                    <!-- Rôle -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rôle *</label>
                        <select id="editRole" name="role" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border">
                            <option value="user">Utilisateur</option>
                            <option value="admin">Administrateur</option>
                        </select>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end space-x-3 pt-4 border-t mt-6">
                        <button type="button" onclick="closeEditModal()"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            Annuler
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Modifier l'utilisateur
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        feather.replace();

        // Fonctions pour le modal d'ajout
        function openAddModal() {
            document.getElementById('add-modal-overlay').classList.add('active');
        }

        function closeAddModal() {
            document.getElementById('add-modal-overlay').classList.remove('active');
        }

        // Fonctions pour le modal d'édition
        function openEditModal(id, name, email, role) {

            const form = document.getElementById('editUserForm');
            form.action = form.getAttribute('data-base-url').replace('user_id', id);


            // Remplir les champs
            document.getElementById("editName").value = name;
            document.getElementById("editEmail").value = email;
            document.getElementById("editRole").value = role;
            // Afficher le modal
            document.getElementById('edit-modal-overlay').classList.add('active');
        }

        function closeEditModal() {
            document.getElementById('edit-modal-overlay').classList.remove('active');
        }

        // Fermer les modals en cliquant à l'extérieur
        document.getElementById('add-modal-overlay')?.addEventListener('click', function(e) {
            if (e.target === this) closeAddModal();
        });

        document.getElementById('edit-modal-overlay')?.addEventListener('click', function(e) {
            if (e.target === this) closeEditModal();
        });

        // Fermer les modals avec la touche Échap
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAddModal();
                closeEditModal();
            }
        });

        // Confirmation de suppression
        function confirmDelete(event) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
                event.preventDefault();
            }
        }
    </script>
@endpush
