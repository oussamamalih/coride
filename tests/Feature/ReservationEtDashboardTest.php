<?php

namespace Tests\Feature;

use App\Models\Entreprise;
use App\Models\Reservation;
use App\Models\Trajet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationEtDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function creerUtilisateur(Entreprise $entreprise, string $email): User
    {
        return User::create([
            'name' => 'Utilisateur '.$email,
            'email' => $email,
            'password' => 'password',
            'entreprise_id' => $entreprise->id,
            'ville_residence' => 'Casablanca',
            'role' => 'passager',
        ]);
    }

    public function test_un_passager_ne_peut_pas_reserver_deux_fois_le_meme_trajet(): void
    {
        $entreprise = Entreprise::create(['nom' => 'MobiliTech']);
        $conducteur = $this->creerUtilisateur($entreprise, 'conducteur@mobilitech.com');
        $passager = $this->creerUtilisateur($entreprise, 'passager@mobilitech.com');

        $trajet = Trajet::create([
            'conducteur_id' => $conducteur->id,
            'ville_depart' => 'Casablanca',
            'ville_arrivee' => 'Rabat',
            'horaire' => '08:00',
            'places_disponibles' => 3,
        ]);

        $this->actingAs($passager)
            ->post(route('reservations.store', $trajet), ['passager_id' => $passager->id])
            ->assertRedirect();

        // Deuxième tentative sur le même trajet : doit être bloquée (check applicatif + contrainte DB).
        $this->actingAs($passager)
            ->post(route('reservations.store', $trajet), ['passager_id' => $passager->id])
            ->assertRedirect();

        $this->assertEquals(1, Reservation::where('trajet_id', $trajet->id)
            ->where('passager_id', $passager->id)
            ->count());
    }

    public function test_un_utilisateur_ne_peut_pas_voir_le_dashboard_dun_autre(): void
    {
        $entreprise = Entreprise::create(['nom' => 'MobiliTech']);
        $userA = $this->creerUtilisateur($entreprise, 'userA@mobilitech.com');
        $userB = $this->creerUtilisateur($entreprise, 'userB@mobilitech.com');

        // La route ne prend plus d'ID : on ne peut voir que son propre dashboard.
        $response = $this->actingAs($userA)->get(route('dashboard'));

        $response->assertOk();
        $response->assertViewHas('user', fn ($user) => $user->id === $userA->id);
    }
}
