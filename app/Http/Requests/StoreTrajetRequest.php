<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTrajetRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Seuls les conducteurs peuvent créer un trajet
        return $this->user()?->estConducteur() ?? false;
    }

    public function rules(): array
    {
        return [
            'ville_depart'       => ['required', 'string', 'max:100'],
            'ville_arrivee'      => ['required', 'string', 'max:100', 'different:ville_depart'],
            'horaire'            => ['required', 'date_format:H:i'],
            'places_disponibles' => ['required', 'integer', 'min:1', 'max:8'],
            'jours_recurrence'   => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'ville_arrivee.different'     => 'La ville d\'arrivée doit être différente de la ville de départ.',
            'places_disponibles.min'      => 'Vous devez proposer au moins 1 place.',
            'places_disponibles.max'      => 'Maximum 8 places par trajet.',
            'horaire.date_format'         => 'L\'horaire doit être au format HH:MM (ex: 08:30).',
            'ville_depart.required'       => 'La ville de départ est obligatoire.',
            'ville_arrivee.required'      => 'La ville d\'arrivée est obligatoire.',
            'jours_recurrence.required'   => 'Les jours de récurrence sont obligatoires.',
        ];
    }
}
