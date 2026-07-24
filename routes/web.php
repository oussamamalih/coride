<?php

use App\Http\Controllers\DashboardConducteurController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ScoreCompatibiliteController;
use App\Http\Controllers\TrajetController;
use Illuminate\Support\Facades\Route;

// ─── Page d'accueil ──────────────────────────────────────────────────────────
Route::get('/', function () {
    return redirect()->route('trajets.index');
});

// ─── Dashboard général ───────────────────────────────────────────────────────
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ─── Trajets publics (lecture seule sans auth) ────────────────────────────────
Route::get('/trajets', [TrajetController::class, 'index'])->name('trajets.index');
Route::get('/trajets/{trajet}', [TrajetController::class, 'show'])->name('trajets.show');

// ─── Routes authentifiées ─────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Trajets (écriture) — conducteurs
    Route::get('/trajets/create', [TrajetController::class, 'create'])->name('trajets.create');
    Route::post('/trajets', [TrajetController::class, 'store'])->name('trajets.store');
    Route::delete('/trajets/{trajet}', [TrajetController::class, 'destroy'])->name('trajets.destroy');

    // ─── Réservations ────────────────────────────────────────────────────────
    Route::get('/mes-reservations', [ReservationController::class, 'index'])
        ->name('reservations.index');

    Route::post('/reservations', [ReservationController::class, 'store'])
        ->name('reservations.store');

    Route::patch('/reservations/{reservation}', [ReservationController::class, 'update'])
        ->name('reservations.update');

    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])
        ->name('reservations.destroy');

    // ─── Score IA ────────────────────────────────────────────────────────────
    Route::post('/trajets/{trajet}/score', [ScoreCompatibiliteController::class, 'store'])
        ->name('score.store');

    // ─── Dashboard conducteur ─────────────────────────────────────────────────
    Route::get('/conducteur/dashboard', [DashboardConducteurController::class, 'index'])
        ->name('conducteur.dashboard');

    // ─── Profil Breeze ───────────────────────────────────────────────────────
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
