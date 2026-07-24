<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrajetRequest;
use App\Models\Trajet;
use App\Services\AIScoringService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TrajetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AIScoringService $aiService): View
    {
        $trajets = Trajet::with('conducteur')
            ->latest()
            ->paginate(10);

        if (auth()->check()) {
            foreach ($trajets as $trajet) {
                $trajet->ai_score = $aiService->evaluateCompatibility($trajet, auth()->user());
            }
        }

        return view('trajets.index', compact('trajets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('trajets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TrajetRequest $request): RedirectResponse
    {
        Trajet::create([
            ...$request->validated(),
            'conducteur_id' => auth()->id(),
        ]);

        return redirect()
            ->route('trajets.index')
            ->with('success', 'Trajet ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Trajet $trajet, AIScoringService $aiService): View
    {
        $trajet->load(['conducteur', 'reservations.passager']);

        if (auth()->check()) {
            $trajet->ai_score = $aiService->evaluateCompatibility($trajet, auth()->user());
        }

        return view('trajets.show', compact('trajet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trajet $trajet): View
    {
        if ($trajet->conducteur_id !== auth()->id()) {
            abort(403, 'Accès non autorisé.');
        }

        return view('trajets.edit', compact('trajet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TrajetRequest $request, Trajet $trajet): RedirectResponse
    {
        if ($trajet->conducteur_id !== auth()->id()) {
            abort(403, 'Accès non autorisé.');
        }

        $trajet->update($request->validated());

        return redirect()
            ->route('trajets.index')
            ->with('success', 'Trajet modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trajet $trajet): RedirectResponse
    {
        if ($trajet->conducteur_id !== auth()->id()) {
            abort(403, 'Accès non autorisé.');
        }

        if ($trajet->reservations()
            ->where('statut', 'confirmee')
            ->exists()) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Impossible de supprimer un trajet ayant des réservations confirmées.'
                );
        }

        $trajet->delete();

        return redirect()
            ->route('trajets.index')
            ->with('success', 'Trajet supprimé avec succès.');
    }
}