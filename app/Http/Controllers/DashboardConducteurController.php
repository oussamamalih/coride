<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardConducteurController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->estConducteur(), 403, 'Accès réservé aux conducteurs.');

        $trajets = auth()->user()
            ->trajets()
            ->with(['reservations' => fn($q) => $q->with('passager.entreprise')->latest()])
            ->withCount([
                'reservations',
                'reservations as reservations_confirmees_count' => fn($q) => $q->where('statut', 'confirmee'),
                'reservations as reservations_attente_count'    => fn($q) => $q->where('statut', 'en_attente'),
            ])
            ->latest()
            ->get();

        $stats = [
            'total_trajets'       => $trajets->count(),
            'total_reservations'  => $trajets->sum('reservations_count'),
            'confirmees'          => $trajets->sum('reservations_confirmees_count'),
            'en_attente'          => $trajets->sum('reservations_attente_count'),
            'taux_remplissage'    => $trajets->count() > 0
                ? round($trajets->sum('reservations_confirmees_count') / max($trajets->sum('places_disponibles'), 1) * 100)
                : 0,
        ];

        return view('conducteur.dashboard', compact('trajets', 'stats'));
    }
}
