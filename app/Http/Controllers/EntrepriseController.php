<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use Illuminate\Http\Request;

class EntrepriseController extends Controller
{
    /**
     * Affiche les statistiques et informations d'une entreprise.
     */
    public function show(Entreprise $entreprise)
    {
        // Charger les employés et leurs trajets / réservations
        $entreprise->load(['employes.trajets', 'employes.reservations']);

        // Calculs des KPIs
        $totalEmployes = $entreprise->employes->count();
        
        $totalTrajets = $entreprise->employes->sum(function ($employe) {
            return $employe->trajets->count();
        });

        $totalReservations = $entreprise->employes->sum(function ($employe) {
            return $employe->reservations->count();
        });

        $reservationsConfirmees = $entreprise->employes->sum(function ($employe) {
            return $employe->reservations->where('statut', 'confirmee')->count();
        });

        // Simulation d'impact écologique
        $co2Economise = $reservationsConfirmees * 15 * 0.12; // 15km par trajet, 120g CO2 par km (0.12 kg)

        return view('entreprises.show', compact(
            'entreprise',
            'totalEmployes',
            'totalTrajets',
            'totalReservations',
            'co2Economise'
        ));
    }
}
