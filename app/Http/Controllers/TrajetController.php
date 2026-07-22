<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrajetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $trajets = Trajet::with('conducteur')->latest()->paginate(10);
    return view('trajets.index', compact('trajets'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('trajets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TrajetRequest $request)
{
    Trajet::create([
        'conducteur_id' => auth()->id(),
        'ville_depart' => $request->ville_depart,
        'ville_arrivee' => $request->ville_arrivee,
        'horaire' => $request->horaire,
        'places_disponibles' => $request->places_disponibles,
        'jours_recurrence' => $request->jours_recurrence,
    ]);

    return redirect()->route('trajets.index')
        ->with('success', 'Trajet ajouté avec succès.');
}

    /**
     * Display the specified resource.
     */
    public function show(Trajet $trajet)
    {
    return view('trajets.show', compact('trajet'));
   }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trajet $trajet)
    {
    return view('trajets.edit', compact('trajet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TrajetRequest $request, Trajet $trajet)
    {
    $trajet->update($request->validated());

    return redirect()->route('trajets.index')
        ->with('success', 'Trajet modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trajet $trajet)
    {
    if ($trajet->reservations()
        ->where('statut', 'confirmee')
        ->exists()) {

        return back()->with(
            'error',
            'Impossible de supprimer un trajet ayant des réservations confirmées.'
        );
    }

    $trajet->delete();

    return redirect()->route('trajets.index')
        ->with('success', 'Trajet supprimé.');
    }
}
