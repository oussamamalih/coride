<?php

namespace App\Services;

use App\Ai\Agents\ScoreCompatibiliteAgent;
use App\Models\Reservation;
use App\Models\Trajet;
use App\Models\User;
use App\ValueObjects\ScoreCompatibilite;
use Throwable;

class ScoreCompatibiliteService
{
    /**
     * Calcule et persiste le score de compatibilité IA pour une réservation donnée.
     */
    public function calculerEtSauvegarder(Reservation $reservation): ScoreCompatibilite
    {
        $trajet  = $reservation->trajet()->with('conducteur.entreprise')->first();
        $passager = $reservation->passager()->with('entreprise')->first();

        $score = $this->calculer($trajet, $passager);

        $reservation->score_compatibilite = $score;
        $reservation->save();

        return $score;
    }

    /**
     * Calcule le score sans persister (pour prévisualisation depuis la liste des trajets).
     */
    public function calculer(Trajet $trajet, User $passager): ScoreCompatibilite
    {
        $prompt = $this->construirePrompt($trajet, $passager);

        try {
            $response = ScoreCompatibiliteAgent::make()->prompt($prompt);
            $data = $response->json();

            return ScoreCompatibilite::fromArray([
                'score'          => $data['score'] ?? 50,
                'justification'  => $data['justification'] ?? 'Score calculé automatiquement.',
                'horaire_suggere'=> $data['horaire_suggere'] ?? $trajet->horaire_formate,
                'points_forts'   => $data['points_forts'] ?? [],
                'points_faibles' => $data['points_faibles'] ?? [],
            ]);
        } catch (Throwable $e) {
            // Fallback si l'API IA est indisponible
            return $this->scoreFallback($trajet, $passager);
        }
    }

    /**
     * Score de secours basé sur des règles simples si l'IA est indisponible.
     */
    private function scoreFallback(Trajet $trajet, User $passager): ScoreCompatibilite
    {
        $score = 40; // Base

        // Bonus ville de résidence = ville de départ
        if (mb_strtolower($passager->ville_residence ?? '') === mb_strtolower($trajet->ville_depart)) {
            $score += 30;
        }

        // Bonus même entreprise
        if ($passager->entreprise_id && $trajet->conducteur && $passager->entreprise_id === $trajet->conducteur->entreprise_id) {
            $score += 20;
        }

        // Bonus trajet quotidien
        if ($trajet->jours_recurrence === 'Tous les jours') {
            $score += 10;
        }

        return ScoreCompatibilite::fromArray([
            'score'          => min($score, 100),
            'justification'  => 'Score calculé hors connexion IA. Résultat basé sur la ville de départ et l\'entreprise.',
            'horaire_suggere'=> $trajet->horaire_formate,
            'points_forts'   => ['Départ depuis votre ville'],
            'points_faibles' => ['Score non calculé par IA (service indisponible)'],
        ]);
    }

    private function construirePrompt(Trajet $trajet, User $passager): string
    {
        $conducteurEntreprise = $trajet->conducteur?->entreprise?->nom ?? 'non renseignée';
        $passagerEntreprise   = $passager->entreprise?->nom ?? 'non renseignée';

        return <<<TEXT
Analyse la compatibilité du trajet suivant pour ce passager :

TRAJET PROPOSÉ :
- Départ : {$trajet->ville_depart}
- Arrivée : {$trajet->ville_arrivee}
- Horaire : {$trajet->horaire_formate}
- Jours : {$trajet->jours_recurrence}
- Places disponibles : {$trajet->places_disponibles}
- Entreprise du conducteur : {$conducteurEntreprise}

PROFIL DU PASSAGER :
- Ville de résidence : {$passager->ville_residence}
- Entreprise : {$passagerEntreprise}
- Rôle habituel : {$passager->role_libelle}

Donne un score de compatibilité objectif et une justification claire.
TEXT;
    }
}
