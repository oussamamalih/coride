<?php

use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TrajetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Models\Entreprise;
use App\Models\Trajet;
use App\Models\User;
use App\Models\Reservation;

// Accueil avec statistiques globales (accessible à tous)
Route::get('/', function () {
    $stats = [
        'entreprises' => Entreprise::count(),
        'salaries' => User::count(),
        'trajets' => Trajet::count(),
        'co2' => Reservation::where('statut', 'confirmee')->count() * 15 * 0.12, // 120g CO2 par km économisé
    ];

    return view('welcome', compact('stats'));
});

// Routes protégées par l'authentification
Route::middleware('auth')->group(function () {
    
    // Page de profil Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes pour les Trajets
    Route::get('/trajets', [TrajetController::class, 'index'])->name('trajets.index');
    Route::get('/trajets/creer', [TrajetController::class, 'create'])->name('trajets.create');
    Route::post('/trajets', [TrajetController::class, 'store'])->name('trajets.store');
    Route::get('/trajets/{trajet}', [TrajetController::class, 'show'])->name('trajets.show');
    Route::get('/trajets/{trajet}/modifier', [TrajetController::class, 'edit'])->name('trajets.edit');
    Route::put('/trajets/{trajet}', [TrajetController::class, 'update'])->name('trajets.update');
    Route::delete('/trajets/{trajet}', [TrajetController::class, 'destroy'])->name('trajets.destroy');

    // Route de réservation
    Route::post('/trajets/{trajet}/reservations', [ReservationController::class, 'store'])->name('reservations.store');

    // Route Dashboard (espace personnel de l'utilisateur connecté uniquement)
    Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');

    // Actions sur les réservations
    Route::post('/reservations/{reservation}/accepter', [ReservationController::class, 'accepter'])->name('reservations.accepter');
    Route::post('/reservations/{reservation}/refuser', [ReservationController::class, 'refuser'])->name('reservations.refuser');
    Route::post('/reservations/{reservation}/annuler', [ReservationController::class, 'annuler'])->name('reservations.annuler');

    // Route pour les entreprises
    Route::get('/entreprises/{entreprise}', [EntrepriseController::class, 'show'])->name('entreprises.show');

});

require __DIR__.'/auth.php';
