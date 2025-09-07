
@extends('layouts.site')

@section('title', 'Connexion')

@section('content')
    <!-- Contact Section -->
    <section class="py-12 px-4 md:px-8 bg-gray-50">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <form method="POST" action="{{ route('login') }}" class="px-8 py-10">
                @csrf
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Connexion</h2>

                <!-- Pseudo -->
                <div class="mb-6">
                    <label for="email" class="block text-gray-700 font-medium mb-2">Email </label>
                    <input
                        type="text"
                        name="email"
                        id="email"
                        placeholder="Entrez votre email "
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-colors duration-200"
                    />
                    @error('email')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mot de passe -->
                <div class="mb-8">
                    <label for="password" class="block text-gray-700 font-medium mb-2">Mot de passe</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Entrez votre mot de passe"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-colors duration-200"
                    />
                    @error('password')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bouton -->
                <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-opacity-50">
                    Se connecter
                </button>

                <!-- Lien d'inscription -->
                <div class="text-center mt-6">
                    <p class="text-gray-600">Pas encore de compte ?
                        <a href="{{ route('register') }}" class="text-amber-600 hover:text-amber-700 font-medium transition-colors duration-200">
                            Créer un compte
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </section>
@endsection
