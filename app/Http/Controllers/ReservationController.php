<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\Reservation;
use App\Models\Trajet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function index(): View
    {
        $reservations = auth()->user()
            ->reservations()
            ->with(['trajet.conducteur.entreprise'])
            ->latest()
            ->paginate(10);

        return view('reservations.index', compact('reservations'));
    }

    public function store(StoreReservationRequest $request): RedirectResponse
    {
        $trajet = Trajet::findOrFail($request->trajet_id);

        // Vérifier que le passager n'est pas le conducteur
        if ($trajet->conducteur_id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas réserver votre propre trajet.');
        }

        // Vérifier les places disponibles
        $placesConfirmees = $trajet->reservationsConfirmees()->count();
        if ($placesConfirmees >= $trajet->places_disponibles) {
            return back()->with('error', 'Ce trajet est complet, aucune place disponible.');
        }

        Reservation::create([
            'trajet_id'        => $trajet->id,
            'passager_id'      => auth()->id(),
            'statut'           => 'en_attente',
            'date_reservation' => now()->toDateString(),
        ]);

        return redirect()
            ->route('reservations.index')
            ->with('success', 'Votre réservation a été envoyée ! En attente de confirmation du conducteur.');
    }

    public function update(Request $request, Reservation $reservation): RedirectResponse
    {
        $nouveauStatut = $request->statut;

        // Le conducteur peut confirmer/refuser
        $estConducteur = $reservation->trajet->conducteur_id === auth()->id();
        // Le passager peut annuler
        $estPassager = $reservation->passager_id === auth()->id();

        abort_unless($estConducteur || $estPassager, 403);

        // Vérifier la transition
        if (! $reservation->peutTransitionnerVers($nouveauStatut)) {
            return back()->with('error', 'Transition de statut non autorisée.');
        }

        // Si confirmation, vérifier qu'il reste des places
        if ($nouveauStatut === 'confirmee') {
            $placesRestantes = $reservation->trajet->places_disponibles
                - $reservation->trajet->reservationsConfirmees()->count();

            if ($placesRestantes <= 0) {
                return back()->with('error', 'Plus de places disponibles sur ce trajet.');
            }
        }

        $reservation->changerStatut($nouveauStatut);

        $message = match ($nouveauStatut) {
            'confirmee' => 'Réservation confirmée.',
            'refusee'   => 'Réservation refusée.',
            'annulee'   => 'Réservation annulée.',
            default     => 'Statut mis à jour.',
        };

        return back()->with('success', $message);
    }

    public function destroy(Reservation $reservation): RedirectResponse
    {
        abort_unless($reservation->passager_id === auth()->id(), 403);

        if (! $reservation->peutTransitionnerVers('annulee')) {
            return back()->with('error', 'Vous ne pouvez pas annuler cette réservation.');
        }

        $reservation->changerStatut('annulee');

        return redirect()
            ->route('reservations.index')
            ->with('success', 'Réservation annulée.');
    }
}
