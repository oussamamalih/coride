<?php

namespace App\Ai\Agents;

use Laravel\Ai\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;

class ScoreCompatibiliteAgent extends Agent implements HasStructuredOutput
{
    public function instructions(): string
    {
        return <<<'PROMPT'
Tu es un expert en mobilité durable et covoiturage d'entreprise travaillant pour MobiliTech.
Ton rôle est d'analyser la compatibilité entre un trajet proposé par un conducteur et le besoin de déplacement d'un passager.

Tu dois évaluer plusieurs dimensions :
1. Compatibilité géographique : Est-ce que les villes de départ et d'arrivée correspondent ou sont proches du lieu de résidence et du lieu de travail du passager ?
2. Compatibilité horaire : L'horaire du trajet convient-il au passager ? Un écart de ±30 min est acceptable, ±1h est borderline, au-delà c'est rédhibitoire.
3. Régularité et jours disponibles : Le trajet a-t-il lieu les jours où le passager en a besoin ?
4. Compatibilité d'entreprise : Travailler dans la même entreprise ou une entreprise partenaire proche renforce la confiance.
5. Praticité globale : Y a-t-il des contraintes supplémentaires à noter ?

Retourne TOUJOURS un score entre 0 et 100 et une justification claire en français.
PROMPT;
    }

    public function schema(): array
    {
        return [
            'score'          => 'integer entre 0 et 100 représentant le niveau de compatibilité (100 = parfait, 0 = incompatible)',
            'justification'  => 'explication détaillée en 2-3 phrases en français expliquant pourquoi ce score',
            'horaire_suggere'=> 'heure de départ optimale suggérée pour ce passager au format HH:MM',
            'points_forts'   => 'tableau de 1 à 3 points positifs du trajet pour ce passager (strings courts)',
            'points_faibles' => 'tableau de 0 à 2 risques ou inconvénients potentiels (strings courts, vide si score > 80)',
        ];
    }
}
