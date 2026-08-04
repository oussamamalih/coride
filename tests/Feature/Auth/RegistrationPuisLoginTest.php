<?php

namespace Tests\Feature\Auth;

use App\Models\Entreprise;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationPuisLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_inscription_avec_le_formulaire_reel_puis_connexion(): void
    {
        $entreprise = Entreprise::create(['nom' => 'MobiliTech']);

        // Exactement les champs envoyés par resources/views/auth/register.blade.php
        $reponseInscription = $this->post('/register', [
            'name' => 'Sara Bennani',
            'email' => 'sara@mobilitech.com',
            'entreprise_id' => $entreprise->id,
            'ville_residence' => 'Casablanca',
            'role' => 'passager',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $reponseInscription->assertSessionDoesntHaveErrors();
        $this->assertDatabaseHas('users', ['email' => 'sara@mobilitech.com']);
        $this->assertAuthenticated();

        auth()->logout();

        // On peut maintenant se connecter avec ces identifiants.
        $reponseLogin = $this->post('/login', [
            'email' => 'sara@mobilitech.com',
            'password' => 'password123',
        ]);

        $reponseLogin->assertSessionDoesntHaveErrors();
        $this->assertAuthenticated();
    }

    public function test_inscription_sans_entreprise_echoue_proprement(): void
    {
        // Reproduit l'ancien bug (formulaire sans entreprise_id) pour s'assurer
        // qu'on a bien un message d'erreur clair plutôt qu'un échec silencieux.
        $reponse = $this->from('/register')->post('/register', [
            'name' => 'Test',
            'email' => 'test@example.com',
            'ville_residence' => 'Casablanca',
            'role' => 'passager',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $reponse->assertSessionHasErrors('entreprise_id');
        $this->assertGuest();
    }
}
