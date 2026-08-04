<?php

namespace Tests\Feature;

use App\Models\Entreprise;
use App\Models\Reservation;
use App\Models\Trajet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrajetOwnershipTest extends TestCase
{
    use RefreshDatabase;

    protected function creerUtilisateur(Entreprise $entreprise, string $email, string $ville = 'Casablanca'): User
    {
        return User::create([
            'name' => 'Utilisateur '.$email,
            'email' => $email,
            'password' => 'password',
            'entreprise_id' => $entreprise->id,
            'ville_residence' => $ville,
            'role' => 'conducteur',
        ]);
    }

    public function test_seul_le_conducteur_proprietaire_peut_modifier_son_trajet(): void
    {
        $entreprise = Entreprise::create(['nom' => 'MobiliTech']);
        $conducteur = $this->creerUtilisateur($entreprise, 'conducteur@mobilitech.com');
        $autreUtilisateur = $this->creerUtilisateur($entreprise, 'autre@mobilitech.com');

        $trajet = Trajet::create([
            'conducteur_id' => $conducteur->id,
            'ville_depart' => 'Casablanca',
            'ville_arrivee' => 'Rabat',
            'horaire' => '08:00',
            'places_disponibles' => 3,
        ]);

        // Un autre utilisateur ne peut pas accéder au formulaire de modification.
        $this->actingAs($autreUtilisateur)
            ->get(route('trajets.edit', $trajet))
            ->assertForbidden();

        // Le propriétaire peut modifier son trajet.
        $this->actingAs($conducteur)
            ->put(route('trajets.update', $trajet), [
                'ville_depart' => 'Casablanca',
                'ville_arrivee' => 'Tanger',
                'horaire' => '09:00',
                'places_disponibles' => 2,
            ])
            ->assertRedirect(route('trajets.show', $trajet));

        $this->assertEquals('Tanger', $trajet->fresh()->ville_arrivee);
    }

    public function test_suppression_bloquee_si_reservation_confirmee(): void
    {
        $entreprise = Entreprise::create(['nom' => 'MobiliTech']);
        $conducteur = $this->creerUtilisateur($entreprise, 'conducteur2@mobilitech.com');
        $passager = $this->creerUtilisateur($entreprise, 'passager2@mobilitech.com');

        $trajet = Trajet::create([
            'conducteur_id' => $conducteur->id,
            'ville_depart' => 'Casablanca',
            'ville_arrivee' => 'Rabat',
            'horaire' => '08:00',
            'places_disponibles' => 3,
        ]);

        Reservation::create([
            'trajet_id' => $trajet->id,
            'passager_id' => $passager->id,
            'statut' => 'confirmee',
        ]);

        $this->actingAs($conducteur)
            ->delete(route('trajets.destroy', $trajet))
            ->assertRedirect(route('trajets.show', $trajet));

        $this->assertNotNull($trajet->fresh(), 'Le trajet ne doit pas être supprimé.');
    }

    public function test_suppression_possible_sans_reservation_confirmee(): void
    {
        $entreprise = Entreprise::create(['nom' => 'MobiliTech']);
        $conducteur = $this->creerUtilisateur($entreprise, 'conducteur3@mobilitech.com');

        $trajet = Trajet::create([
            'conducteur_id' => $conducteur->id,
            'ville_depart' => 'Casablanca',
            'ville_arrivee' => 'Rabat',
            'horaire' => '08:00',
            'places_disponibles' => 3,
        ]);

        $this->actingAs($conducteur)
            ->delete(route('trajets.destroy', $trajet))
            ->assertRedirect(route('trajets.index'));

        $this->assertNull($trajet->fresh());
    }
}
