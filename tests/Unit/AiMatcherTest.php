<?php

namespace Tests\Unit;

use App\Models\Entreprise;
use App\Models\Trajet;
use App\Models\User;
use App\Services\AiMatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AiMatcherTest extends TestCase
{
    use RefreshDatabase; // Réinitialise la base de données de test à chaque exécution

    /**
     * Teste une compatibilité maximale (même ville et même entreprise).
     */
    public function test_compatibilite_maximale(): void
    {
        // 1. Préparation des données (Arrange)
        $entreprise = Entreprise::create([
            'nom' => 'MobiliTech',
            'secteur_activite' => 'Tech',
            'adresse' => 'Rabat'
        ]);

        $conducteur = User::create([
            'name' => 'Ahmed Alami',
            'email' => 'ahmed@mobilitech.com',
            'password' => 'password',
            'entreprise_id' => $entreprise->id,
            'ville_residence' => 'Casablanca',
            'role' => 'conducteur'
        ]);

        $passager = User::create([
            'name' => 'Sara Bennani',
            'email' => 'sara@mobilitech.com',
            'password' => 'password',
            'entreprise_id' => $entreprise->id,
            'ville_residence' => 'Casablanca', // Même ville de résidence que le départ du trajet
            'role' => 'passager'
        ]);

        $trajet = Trajet::create([
            'conducteur_id' => $conducteur->id,
            'ville_depart' => 'Casablanca',
            'ville_arrivee' => 'Rabat',
            'horaire' => '08:00',
            'places_disponibles' => 3
        ]);

        $matcher = new AiMatcher();

        // 2. Exécution de la méthode (Act)
        $resultat = $matcher->calculateCompatibility($passager, $trajet);

        // 3. Vérifications (Assert)
        $this->assertEquals(100, $resultat->score); // 30 base + 40 même ville + 30 même entreprise = 100 max
        $this->assertStringContainsString('Même ville de départ', $resultat->justification);
        $this->assertStringContainsString('Salariés de la même entreprise', $resultat->justification);
    }

    /**
     * Teste une compatibilité minimale (ville différente et entreprise différente).
     */
    public function test_compatibilite_minimale(): void
    {
        // 1. Préparation (Arrange)
        $ent1 = Entreprise::create(['nom' => 'Company A']);
        $ent2 = Entreprise::create(['nom' => 'Company B']);

        $conducteur = User::create([
            'name' => 'John Doe',
            'email' => 'john@companya.com',
            'password' => 'password',
            'entreprise_id' => $ent1->id,
            'ville_residence' => 'Rabat',
            'role' => 'conducteur'
        ]);

        $passager = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@companyb.com',
            'password' => 'password',
            'entreprise_id' => $ent2->id, // Entreprise différente
            'ville_residence' => 'Casablanca', // Ville différente du départ du trajet
            'role' => 'passager'
        ]);

        $trajet = Trajet::create([
            'conducteur_id' => $conducteur->id,
            'ville_depart' => 'Tangier', // Départ Tangier (différent de Casablanca)
            'ville_arrivee' => 'Rabat',
            'horaire' => '08:00',
            'places_disponibles' => 3
        ]);

        $matcher = new AiMatcher();

        // 2. Exécution (Act)
        $resultat = $matcher->calculateCompatibility($passager, $trajet);

        // 3. Vérifications (Assert)
        $this->assertEquals(45, $resultat->score); // 30 base + 0 ville + 0 entreprise + 15 horaire = 45%
        $this->assertStringContainsString('Ville de résidence différente', $resultat->justification);
        $this->assertStringNotContainsString('Salariés de la même entreprise', $resultat->justification);
    }
}
