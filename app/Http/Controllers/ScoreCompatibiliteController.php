<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Trajet;
use App\Services\ScoreCompatibiliteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class ScoreCompatibiliteController extends Controller
{
    public function __construct(private ScoreCompatibiliteService $service) {}

    /**
     * Calcule le score IA et le stocke sur la réservation existante.
     * Si pas encore de réservation, crée une prévisualisation.
     */
    public function store(Trajet $trajet): RedirectResponse
    {
        $user = auth()->user();

        // Le score est uniquement pour les passagers
        abort_unless($user->estPassager(), 403, 'Le score de compatibilité est réservé aux passagers.');

        // Trouver ou créer la réservation associée
        $reservation = Reservation::where('trajet_id', $trajet->id)
            ->where('passager_id', $user->id)
            ->first();

        if (! $reservation) {
            // Prévisualisation : on crée une réservation temporaire "en_attente"
            $reservation = Reservation::create([
                'trajet_id'        => $trajet->id,
                'passager_id'      => $user->id,
                'statut'           => 'en_attente',
                'date_reservation' => now()->toDateString(),
            ]);
        }

        $trajet->load('conducteur.entreprise');
        $user->load('entreprise');

        $this->service->calculerEtSauvegarder($reservation);

        return redirect()
            ->route('trajets.show', $trajet)
            ->with('success', 'Score de compatibilité calculé par l\'IA !');
    }
}
