<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;
use App\Models\Trajet;
use App\Services\AIScoringService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservationController extends Controller
{
    /**
     * Display a listing of the user's reservations.
     */
    public function index(): View
    {
        $reservations = Reservation::with([
                'trajet.conducteur',
                'passager'
            ])
            ->where('passager_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('reservations.index', compact('reservations'));
    }

    /**
     * Store a newly created reservation.
     */
    public function store(ReservationRequest $request, AIScoringService $aiService): RedirectResponse
    {
        $trajet = Trajet::findOrFail($request->trajet_id);

        // Un conducteur ne peut pas réserver son propre trajet
        if ($trajet->conducteur_id === auth()->id()) {
            return back()->with(
                'error',
                'Vous ne pouvez pas réserver votre propre trajet.'
            );
        }

        // Vérification des places disponibles
        $placesOccupees = $trajet->reservations()
            ->where('statut', 'confirmee')
            ->count();

        if ($placesOccupees >= $trajet->places_disponibles) {
            return back()->with(
                'error',
                'Plus aucune place disponible.'
            );
        }

        // Vérification des doublons
        if (
            Reservation::where('trajet_id', $trajet->id)
                ->where('passager_id', auth()->id())
                ->exists()
        ) {
            return back()->with(
                'error',
                'Vous avez déjà réservé ce trajet.'
            );
        }

        $resultatIa = $aiService->evaluateCompatibility($trajet, auth()->user());

        Reservation::create([
            'trajet_id' => $trajet->id,
            'passager_id' => auth()->id(),
            'statut' => 'en_attente',
            'date_reservation' => now(),
            'resultat_ia' => $resultatIa, // rempli par le service IA
        ]);

        return redirect()
            ->route('reservations.index')
            ->with('success', 'Réservation enregistrée avec succès.');
    }

    /**
     * Update reservation status.
     * Seul le conducteur peut confirmer/refuser.
     */
    public function update(Request $request, Reservation $reservation): RedirectResponse
    {
        $request->validate([
            'statut' => 'required|in:confirmee,refusee',
        ]);

        $trajet = $reservation->trajet;

        if ($trajet->conducteur_id !== auth()->id()) {
            abort(403);
        }

        if ($reservation->statut !== 'en_attente') {
            return back()->with(
                'error',
                'Seule une réservation en attente peut être modifiée.'
            );
        }

        $placesOccupees = $trajet->reservations()
            ->where('statut', 'confirmee')
            ->count();

        if (
            $request->statut === 'confirmee'
            && $placesOccupees >= $trajet->places_disponibles
        ) {
            return back()->with(
                'error',
                'Plus aucune place disponible.'
            );
        }

        $reservation->update([
            'statut' => $request->statut,
        ]);

        return back()->with(
            'success',
            'Statut de la réservation mis à jour.'
        );
    }

    /**
     * Cancel a reservation.
     */
    public function destroy(Reservation $reservation): RedirectResponse
    {
        if ($reservation->passager_id !== auth()->id()) {
            abort(403);
        }

        if (! in_array($reservation->statut, [
            'en_attente',
            'confirmee'
        ])) {

            return back()->with(
                'error',
                'Cette réservation ne peut plus être annulée.'
            );
        }

        $reservation->update([
            'statut' => 'annulee',
        ]);

        return back()->with(
            'success',
            'Réservation annulée avec succès.'
        );
    }
}