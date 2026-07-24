<?php

namespace App\Services;

use App\Models\Trajet;
use App\Models\User;
use Exception;

class AIScoringService
{
    /**
     * Evalue la compatibilité entre un trajet et un utilisateur.
     */
    public function evaluateCompatibility(Trajet $trajet, User $passager): array
    {
        try {
            // Tentative d'utilisation de Laravel AI (si configuré)
            // Dans une vraie application, cela ferait appel au LLM configuré :
            /*
            $response = \Laravel\Ai\Ai::chat()->ask(
                "Évalue la compatibilité entre un passager habitant à {$passager->ville_residence} " .
                "et ce trajet: départ {$trajet->ville_depart}, arrivée {$trajet->ville_arrivee} à {$trajet->horaire->format('H:i')}. " .
                "Renvoie un JSON avec: 'score' (0-100), 'justification' (courte), 'horaires_suggeres' (optionnel)."
            );
            $result = json_decode($response->text(), true);
            return $result;
            */
            
            // Simulation pour la démo
            $score = 0;
            $justification = "L'itinéraire proposé correspond à vos critères.";
            
            if (strtolower(trim($passager->ville_residence)) === strtolower(trim($trajet->ville_depart))) {
                $score += 60;
                $justification = "Le départ se trouve dans votre ville de résidence. ";
            } else {
                $score += 30;
                $justification = "Le départ n'est pas dans votre ville, un temps d'approche est requis. ";
            }
            
            $heure = (int) $trajet->horaire->format('H');
            if ($heure >= 7 && $heure <= 9) {
                $score += 35;
                $justification .= "L'horaire est idéal pour un trajet matinal.";
            } elseif ($heure >= 17 && $heure <= 19) {
                $score += 35;
                $justification .= "L'horaire est parfait pour le retour du soir.";
            } else {
                $score += 20;
                $justification .= "L'horaire est en dehors des heures de pointe.";
            }

            return [
                'score' => min(100, $score),
                'justification' => trim($justification),
                'horaires_suggeres' => $trajet->horaire->format('H:i')
            ];

        } catch (Exception $e) {
            return [
                'score' => 50,
                'justification' => 'Score calculé par défaut.',
                'horaires_suggeres' => $trajet->horaire->format('H:i')
            ];
        }
    }
}
