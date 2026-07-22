<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $reservations = Reservation::with(['trajet','passager'])
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
