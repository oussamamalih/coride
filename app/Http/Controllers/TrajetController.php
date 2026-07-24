<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTrajetRequest;
use App\Models\Trajet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrajetController extends Controller
{
    public function index(Request $request): View
    {
        $query = Trajet::with(['conducteur.entreprise', 'reservationsConfirmees'])
            ->withCount('reservationsConfirmees');

        if ($request->filled('ville_depart')) {
            $query->where('ville_depart', 'like', '%' . $request->ville_depart . '%');
        }

        if ($request->filled('ville_arrivee')) {
            $query->where('ville_arrivee', 'like', '%' . $request->ville_arrivee . '%');
        }

        if ($request->filled('horaire')) {
            $query->whereTime('horaire', '>=', $request->horaire)
                  ->whereTime('horaire', '<=', date('H:i', strtotime($request->horaire) + 3600));
        }

        $trajets = $query->latest()->paginate(12)->withQueryString();

        // Charger les réservations de l'utilisateur connecté pour connaître les scores déjà calculés
        $mesReservations = collect();
        if (auth()->check()) {
            $mesReservations = auth()->user()->reservations()
                ->whereIn('trajet_id', $trajets->pluck('id'))
                ->get()
                ->keyBy('trajet_id');
        }

        $villes = Trajet::selectRaw('DISTINCT ville_depart')->pluck('ville_depart')
            ->merge(Trajet::selectRaw('DISTINCT ville_arrivee')->pluck('ville_arrivee'))
            ->unique()->sort()->values();

        return view('trajets.index', compact('trajets', 'mesReservations', 'villes'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()->estConducteur(), 403, 'Vous n\'êtes pas conducteur.');
        return view('trajets.create');
    }

    public function store(StoreTrajetRequest $request): RedirectResponse
    {
        $trajet = Trajet::create([
            ...$request->validated(),
            'conducteur_id' => auth()->id(),
        ]);

        return redirect()
            ->route('trajets.show', $trajet)
            ->with('success', 'Votre trajet a été publié avec succès !');
    }

    public function show(Trajet $trajet): View
    {
        $trajet->load(['conducteur.entreprise', 'reservations.passager.entreprise']);

        $maReservation = null;
        if (auth()->check()) {
            $maReservation = $trajet->reservations
                ->where('passager_id', auth()->id())
                ->first();
        }

        $placesRestantes = $trajet->places_disponibles
            - $trajet->reservations->where('statut', 'confirmee')->count();

        return view('trajets.show', compact('trajet', 'maReservation', 'placesRestantes'));
    }

    public function destroy(Trajet $trajet): RedirectResponse
    {
        abort_unless($trajet->conducteur_id === auth()->id(), 403);

        if ($trajet->aDesReservationsConfirmees()) {
            return back()->with('error', 'Impossible de supprimer ce trajet : il a des réservations confirmées.');
        }

        $trajet->delete();

        return redirect()
            ->route('conducteur.dashboard')
            ->with('success', 'Trajet supprimé avec succès.');
    }
}
