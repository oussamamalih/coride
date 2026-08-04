<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Affiche l'espace personnel (Dashboard) de l'utilisateur connecté.
     * Note : ne prend plus d'ID en paramètre, pour éviter qu'un utilisateur
     * puisse consulter le dashboard d'un autre salarié (faille IDOR).
     */
    public function show()
    {
        $user = auth()->user();

        // Chargement optimisé des relations
        $user->load([
            'entreprise',
            'trajets.reservations.passager',
            'reservations.trajet.conducteur'
        ]);

        return view('trajets.dashboard', compact('user'));
    }
}
