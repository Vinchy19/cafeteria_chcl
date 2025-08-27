<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PlatController;
use App\Http\Controllers\VenteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;

/*
|--------------------------------------------------------------------------
| Routes Publiques (sans authentification)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('accueil');
})->name('accueil');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

     //Routes d'inscription
     Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
     Route::post('/register', [RegisteredUserController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| Routes Authentifiées (pour tous les utilisateurs connectés)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Gestion du profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Déconnexion
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Routes Administrateur
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('projet')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Plats
    Route::resource('plats', PlatController::class)->only([
        'index', 'create', 'store', 'edit', 'update'
    ]);

    // Ventes
    Route::resource('ventes', VenteController::class)->only([
        'index', 'create', 'store', 'edit', 'update'
    ]);

    // Clients
    Route::resource('clients', ClientController::class)->only([
        'index', 'create', 'store', 'edit', 'update'
    ]);
});

/*
|--------------------------------------------------------------------------
| Routes pour les modules
|--------------------------------------------------------------------------
*/


;
// Routes d'authentification générées par Laravel Breeze/
require __DIR__.'/auth.php';
