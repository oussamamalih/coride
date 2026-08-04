<?php

namespace Tests\Feature\Auth;

use App\Models\Entreprise;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        // On crée au moins une entreprise pour le formulaire d'inscription
        Entreprise::create([
            'nom' => 'Test Enterprise',
            'secteur_activite' => 'Tech',
            'adresse' => 'Rabat'
        ]);

        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $entreprise = Entreprise::create([
            'nom' => 'Test Enterprise',
            'secteur_activite' => 'Tech',
            'adresse' => 'Rabat'
        ]);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'entreprise_id' => $entreprise->id,
            'ville_residence' => 'Casablanca',
            'role' => 'passager',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }
}
