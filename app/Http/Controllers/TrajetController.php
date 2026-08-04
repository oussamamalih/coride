<?php

namespace App\Http\Controllers;

use App\Models\Trajet;
use App\Models\User;
use Illuminate\Http\Request;

class TrajetController extends Controller
{
    /**
     * Affiche la liste des trajets avec possibilité de filtrage.
     */
    public function index(Request $request)
    {
        $query = Trajet::with('conducteur');

        $query->when($request->filled('ville_depart'), function ($q) use ($request) {
            return $q->where('ville_depart', 'like', '%' . $request->ville_depart . '%');
        });

        $query->when($request->filled('ville_arrivee'), function ($q) use ($request) {
            return $q->where('ville_arrivee', 'like', '%' . $request->ville_arrivee . '%');
        });

        $trajets = $query->latest()->get();

        return view('trajets.index', compact('trajets'));
    }

    /**
     * Formulaire de création d'un nouveau trajet.
     */
    public function create()
    {
        $conducteurs = User::all();
        return view('trajets.create', compact('conducteurs'));
    }

    /**
     * Enregistre un nouveau trajet dans la base de données.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'conducteur_id' => 'required|exists:users,id',
            'ville_depart' => 'required|string|max:255',
            'ville_arrivee' => 'required|string|max:255',
            'horaire' => 'required|string',
            'places_disponibles' => 'required|integer|min:1|max:8',
            'jours_recurrence' => 'nullable|string|max:255',
        ]);

        $trajet = Trajet::create($validatedData);

        return redirect()->route('trajets.show', $trajet)
            ->with('success', 'Votre trajet a été publié avec succès !');
    }

    /**
     * Affiche les détails d'un trajet spécifique.
     */
    public function show(Trajet $trajet)
    {
         $trajet->load(['conducteur.entreprise', 'reservations.passager']);

        $users = User::whereIn('role', ['passager', 'les_deux'])->get();

    return view('trajets.show', compact('trajet', 'users'));
       
    }

    /**
     * Formulaire de modification d'un trajet (réservé au conducteur propriétaire).
     */
    public function edit(Trajet $trajet)
    {
        $this->autoriserConducteur($trajet);

        return view('trajets.edit', compact('trajet'));
    }

    /**
     * Met à jour un trajet existant (réservé au conducteur propriétaire).
     */
    public function update(Request $request, Trajet $trajet)
    {
        $this->autoriserConducteur($trajet);

        $validatedData = $request->validate([
            'ville_depart' => 'required|string|max:255',
            'ville_arrivee' => 'required|string|max:255',
            'horaire' => 'required|string',
            'places_disponibles' => 'required|integer|min:1|max:8',
            'jours_recurrence' => 'nullable|string|max:255',
        ]);

        $trajet->update($validatedData);

        return redirect()->route('trajets.show', $trajet)
            ->with('success', 'Le trajet a été mis à jour avec succès !');
    }

    /**
     * Supprime un trajet (réservé au conducteur propriétaire).
     * Règle métier : un trajet ne peut pas être supprimé s'il possède
     * au moins une réservation confirmée.
     */
    public function destroy(Trajet $trajet)
    {
        $this->autoriserConducteur($trajet);

        $aUneReservationConfirmee = $trajet->reservations()
            ->where('statut', 'confirmee')
            ->exists();

        if ($aUneReservationConfirmee) {
            return redirect()->route('trajets.show', $trajet)
                ->with('error', 'Impossible de supprimer ce trajet : il possède au moins une réservation confirmée.');
        }

        $trajet->delete();

        return redirect()->route('trajets.index')
            ->with('success', 'Le trajet a été supprimé avec succès.');
    }

    /**
     * Vérifie que l'utilisateur connecté est bien le conducteur propriétaire du trajet.
     */
    protected function autoriserConducteur(Trajet $trajet): void
    {
        if (auth()->id() !== $trajet->conducteur_id) {
            abort(403, "Vous n'êtes pas autorisé à modifier ce trajet.");
        }
    }
}
