<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TrajetController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Trajets
    |--------------------------------------------------------------------------
    */

    Route::resource('trajets', TrajetController::class);

    /*
    |--------------------------------------------------------------------------
    | Réservations
    |--------------------------------------------------------------------------
    */

    Route::resource('reservations', ReservationController::class)
        ->only([
            'index',
            'store',
            'update',
            'destroy',
        ]);
});

require __DIR__.'/auth.php';