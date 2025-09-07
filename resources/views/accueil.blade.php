@extends('layouts.site')

@section('title', 'Accueil')

@section('content')
    <!-- Header -->
    <header class="bg-gradient-to-r from-amber-50 to-amber-100 py-16 px-4 md:px-8">
        <div class="max-w-6xl mx-auto">
            <div class="text-center max-w-3xl mx-auto">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-800 mb-6">
                    Découvrez <span class="text-amber-600">nos saveurs</span> au cœur de votre quotidien
                </h2>
                <p class="text-lg md:text-xl text-gray-600 leading-relaxed">
                    Plongez dans une expérience culinaire unique où chaque plat est préparé avec soin et passion.
                    Savourez des recettes authentiques, créées pour ravir vos papilles.
                </p>
            </div>
        </div>
    </header>

    <!-- Main content -->
    <div class="text-3xl font-bold text-center my-12 text-gray-800">Nos Spécialités Gourmandes</div>

    <!-- Cards Section -->
    <section class="py-8 px-4 md:px-8">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
                <img class="w-full h-48 object-cover" src="{{ asset('images/barbecue.webp') }}" alt="Barbecue">
                <div class="p-4">
                    <p class="font-bold text-lg text-gray-800">Riz au Poulet Rôti</p>
                    <p class="text-gray-600 mt-2">Accompagné d'une sauce savoureuse</p>
                    <div class="border-t border-gray-200 my-3"></div>
                    <div class="prix">
                        <p class="text-amber-700 font-semibold">94$ - 120$</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
                <img class="w-full h-48 object-cover" src="{{ asset('images/pizza.jpg') }}" alt="Pizza">
                <div class="p-4">
                    <p class="font-bold text-lg text-gray-800">Pizza Deluxe</p>
                    <p class="text-gray-600 mt-2">11 ingrédients exquis pour un goût parfait</p>
                    <div class="border-t border-gray-200 my-3"></div>
                    <div class="prix">
                        <p class="text-amber-700 font-semibold">€15 - €20</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
                <img class="w-full h-48 object-cover" src="{{ asset('images/viande.jpeg') }}" alt="Viande">
                <div class="p-4">
                    <p class="font-bold text-lg text-gray-800">Steak de Bœuf</p>
                    <p class="text-gray-600 mt-2">450g avec légumes frais</p>
                    <div class="border-t border-gray-200 my-3"></div>
                    <div class="prix">
                        <p class="text-amber-700 font-semibold">€25 - €30</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
                <img class="w-full h-48 object-cover" src="{{ asset('images/bon_gout.jpg') }}" alt="Plat">
                <div class="p-4">
                    <p class="font-bold text-lg text-gray-800">Pâté Sec</p>
                    <p class="text-gray-600 mt-2">Servi avec une sauce poulet délicieuse</p>
                    <div class="border-t border-gray-200 my-3"></div>
                    <div class="prix">
                        <p class="text-amber-700 font-semibold">9$ - 20$</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Info Section -->
    <section class="py-12 px-4 md:px-8 bg-amber-50">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center gap-8">
            <img class="w-full md:w-1/2 rounded-lg shadow-md" src="{{ asset('images/banner.png') }}" alt="Bannière">
            <div class="w-full md:w-1/2">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">
                    Une équipe de <span class="text-amber-600">chefs passionnés</span> à votre service
                </h2>
                <p class="text-gray-600 leading-relaxed">
                    Avec plus de 10 ans d'expertise, notre équipe se dédie à vous offrir des plats de qualité
                    supérieure, alliant tradition et modernité. Venez partager un moment de bonheur autour de nos
                    créations culinaires.
                </p>
            </div>
        </div>
    </section>
@endsection
