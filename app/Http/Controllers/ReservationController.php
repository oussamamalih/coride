<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;
use App\Models\Trajet;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $reservations = Reservation::with(['trajet','passager'])
                    ->where('passager_id', auth()->id())
                    ->latest()
                    ->paginate(10);

    return view('reservations.index', compact('reservations'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReservationRequest $request)
{
    $trajet = Trajet::findOrFail($request->trajet_id);

    if ($trajet->conducteur_id === auth()->id()) {
        return back()->with(
            'error',
            'Vous ne pouvez pas réserver votre propre trajet.'
        );
    }

    if (
        $trajet->reservations()
            ->where('statut', 'confirmee')
            ->count() >= $trajet->places_disponibles
    ) {
        return back()->with(
            'error',
            'Plus aucune place disponible.'
        );
    }

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

    Reservation::create([
        'trajet_id' => $trajet->id,
        'passager_id' => auth()->id(),
        'statut' => 'en_attente',
        'date_reservation' => now(),
    ]);

    return redirect()->route('reservations.index')
        ->with('success', 'Réservation enregistrée.');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the status of a reservation. Seul le conducteur du trajet
     * concerné peut confirmer ou refuser, et uniquement depuis "en_attente"
     * (transitions controlées, cf. règles de gestion).
     */
    public function update(Request $request, Reservation $reservation)
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
                'Seule une réservation en attente peut être confirmée ou refusée.'
            );
        }

        if (
            $request->statut === 'confirmee'
            && $trajet->reservations()->where('statut', 'confirmee')->count() >= $trajet->places_disponibles
        ) {
            return back()->with(
                'error',
                'Plus aucune place disponible pour confirmer cette réservation.'
            );
        }

        $reservation->update(['statut' => $request->statut]);

        return back()->with('success', 'Réservation mise à jour.');
    }

    /**
     * Annule une réservation. Seul le passager qui l'a faite peut l'annuler,
     * et uniquement si elle n'est pas déjà refusée/annulée (transition controlée).
     */
    public function destroy(Reservation $reservation)
    {
        if ($reservation->passager_id !== auth()->id()) {
            abort(403);
        }

        if (! in_array($reservation->statut, ['en_attente', 'confirmee'])) {
            return back()->with(
                'error',
                'Cette réservation ne peut plus être annulée.'
            );
        }

        $reservation->update(['statut' => 'annulee']);

        return back()->with('success', 'Réservation annulée.');
    }
}
