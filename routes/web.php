<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Home\DashboardController as HomeDashboardController;
use App\Http\Controllers\PlatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VenteController;
use Illuminate\Support\Facades\Route;

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
| Routes auth et verifier
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('projet')->group(function () {
    Route::get('/dashboard', [HomeDashboardController::class, 'index'])->name('dashboard');

    // Plats
    Route::resource('plats', PlatController::class);

    // Ventes
    Route::resource('ventes', VenteController::class);

    // Clients
    Route::resource('clients', ClientController::class);
});

/*
|--------------------------------------------------------------------------
| Routes auth et verifier et admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified','admin'])->prefix('projet')->group(function () {
    //users
    Route::resource('users', UserController::class);
    Route::post('/ventes/pdf',[VenteController::class, 'generatePDF'])->name('ventes.pdf');

});
// Routes d'authentification générées par Laravel Breeze/
require __DIR__ . '/auth.php';
