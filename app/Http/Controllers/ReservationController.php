<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Trajet;
use App\Models\User;
use Illuminate\Http\Request;

use App\Services\AiMatcher;

class ReservationController extends Controller
{
    protected $aiMatcher;

    public function __construct(AiMatcher $aiMatcher)
    {
        $this->aiMatcher = $aiMatcher;
    }

    /**
     * Enregistre une demande de réservation pour un trajet.
     */
    public function store(Request $request, Trajet $trajet)
    {
        $request->validate([
            'passager_id' => 'required|exists:users,id',
        ]);

        $passager = User::findOrFail($request->passager_id);

        if ($trajet->places_disponibles <= 0) {
            return redirect()->back()->with('error', 'Désolé, ce trajet n\'a plus de places disponibles.');
        }

        if ($trajet->conducteur_id == $passager->id) {
            return redirect()->back()->with('error', 'Vous êtes le conducteur de ce trajet !');
        }

        $dejaReserve = Reservation::where('trajet_id', $trajet->id)
            ->where('passager_id', $passager->id)
            ->exists();

        if ($dejaReserve) {
            return redirect()->back()->with('error', 'Vous avez déjà soumis une demande de réservation pour ce trajet.');
        }

        // Calcul dynamique via le service (retourne un objet ScoreCompatibilite)
        $compatibilite = $this->aiMatcher->calculateCompatibility($passager, $trajet);

        try {
            Reservation::create([
                'trajet_id' => $trajet->id,
                'passager_id' => $passager->id,
                'statut' => 'en_attente',
                // Le cast ScoreCompatibiliteCast se charge de la sérialisation JSON,
                // on lui passe directement l'objet (pas de json_encode manuel ici).
                'resultat_ia' => $compatibilite,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Filet de sécurité : la contrainte unique DB (trajet_id, passager_id)
            // couvre le cas de deux requêtes concurrentes passant le check ci-dessus en même temps.
            return redirect()->back()->with('error', 'Vous avez déjà soumis une demande de réservation pour ce trajet.');
        }

        return redirect()->back()->with('success', 'Votre demande de réservation a été enregistrée avec succès !');
    }

    /**
     * Accepte la demande de réservation.
     */
    public function accepter(Reservation $reservation)
    {
        $trajet = $reservation->trajet;

        // Vérifier si le trajet dispose encore de places libres
        if ($trajet->places_disponibles <= 0) {
            return redirect()->back()->with('error', 'Impossible d\'accepter : ce trajet n\'a plus de places disponibles.');
        }

        // Mettre à jour le statut de la réservation
        $reservation->update(['statut' => 'confirmee']);

        // Décrémenter les places sur le trajet
        $trajet->decrement('places_disponibles');

        return redirect()->back()->with('success', 'La réservation a été confirmée avec succès !');
    }

    /**
     * Refuse la demande de réservation.
     */
    public function refuser(Reservation $reservation)
    {
        $reservation->update(['statut' => 'refusee']);

        return redirect()->back()->with('success', 'La réservation a été refusée.');
    }

        /**
     * Annule une réservation par le passager.
     */
    public function annuler(Reservation $reservation)
    {
        $trajet = $reservation->trajet;

        // Si la réservation était confirmée, on libère une place sur le trajet
        if ($reservation->statut === 'confirmee') {
            $trajet->increment('places_disponibles');
        }

        // Mettre à jour le statut
        $reservation->update(['statut' => 'annulee']);

        return redirect()->back()->with('success', 'Votre demande de réservation a bien été annulée.');
    }

}
