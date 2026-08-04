<?php

namespace App\Services;

use App\Ai\Agents\ScoreCompatibiliteAgent;
use App\Models\Trajet;
use App\Models\User;
use App\ValueObjects\ScoreCompatibilite;
use Illuminate\Support\Facades\Log;
use Throwable;

class AiMatcher
{
    /**
     * Calcule le score de compatibilité entre un passager et un trajet.
     *
     * Si un fournisseur d'IA (laravel/ai) est configuré (clé API présente),
     * on délègue le calcul à ScoreCompatibiliteAgent pour une justification
     * réellement générée par un LLM. Sinon (ou en cas d'erreur réseau/API),
     * on retombe sur une heuristique déterministe locale afin que la
     * fonctionnalité reste toujours disponible en démo/hors-ligne.
     */
    public function calculateCompatibility(User $passager, Trajet $trajet): ScoreCompatibilite
    {
        if ($this->aiDisponible()) {
            try {
                return $this->viaAgentIA($passager, $trajet);
            } catch (Throwable $e) {
                Log::warning('AiMatcher: échec de l\'appel IA, bascule sur le calcul local.', [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $this->viaHeuristiqueLocale($passager, $trajet);
    }

    protected function aiDisponible(): bool
    {
        return class_exists(\Laravel\Ai\Agent::class) && filled(config('ai.providers.'.config('ai.default').'.key'));
    }

    /**
     * Calcul via le modèle de langage configuré (laravel/ai).
     */
    protected function viaAgentIA(User $passager, Trajet $trajet): ScoreCompatibilite
    {
        $contexte = <<<TEXT
Passager : réside à {$passager->ville_residence}, entreprise #{$passager->entreprise_id}.
Trajet proposé : {$trajet->ville_depart} → {$trajet->ville_arrivee}, horaire {$trajet->horaire}, jours {$trajet->jours_recurrence}.
Conducteur : entreprise #{$trajet->conducteur->entreprise_id}.
TEXT;

        $reponse = (new ScoreCompatibiliteAgent())->respond($contexte);

        return ScoreCompatibilite::fromArray((array) $reponse);
    }

    /**
     * Heuristique locale déterministe (aucun appel réseau requis).
     */
    protected function viaHeuristiqueLocale(User $passager, Trajet $trajet): ScoreCompatibilite
    {
        $score = 30; // Score de base
        $pointsForts = [];
        $pointsFaibles = [];

        // 1. Analyse de la ville de départ
        if (strcasecmp(trim($passager->ville_residence), trim($trajet->ville_depart)) === 0) {
            $score += 40;
            $pointsForts[] = 'Même ville de départ ('.$trajet->ville_depart.')';
        } else {
            $pointsFaibles[] = 'Ville de résidence différente de la ville de départ ('.$passager->ville_residence.')';
        }

        // 2. Analyse de l'entreprise (covoiturage intra-entreprise)
        if ($passager->entreprise_id === $trajet->conducteur->entreprise_id) {
            $score += 30;
            $pointsForts[] = 'Salariés de la même entreprise ('.($passager->entreprise->nom ?? 'CoRide').')';
        }

        // 3. Bonus horaire
        $score += 15;
        $pointsForts[] = 'Horaire compatible ('.$trajet->horaire.')';

        $score = min($score, 100);

        $justification = 'Trajet '.($score >= 80 ? 'très recommandé' : ($score >= 50 ? 'recommandé' : 'peu adapté')).
            '. Points forts : '.implode(', ', $pointsForts).'.';

        if (! empty($pointsFaibles)) {
            $justification .= ' Points faibles : '.implode(', ', $pointsFaibles).'.';
        }

        return new ScoreCompatibilite(
            score: $score,
            justification: $justification,
            horaire_suggere: $trajet->horaire,
            points_forts: $pointsForts,
            points_faibles: $pointsFaibles,
        );
    }
}
