<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->estPassager() ?? false;
    }

    public function rules(): array
    {
        return [
            'trajet_id' => [
                'required',
                'exists:trajets,id',
                // Un passager ne peut pas réserver deux fois le même trajet
                Rule::unique('reservations')->where(function ($query) {
                    return $query->where('passager_id', $this->user()->id)
                                 ->whereNotIn('statut', ['annulee', 'refusee']);
                }),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'trajet_id.unique'    => 'Vous avez déjà une réservation active pour ce trajet.',
            'trajet_id.exists'    => 'Ce trajet n\'existe pas.',
            'trajet_id.required'  => 'Le trajet est requis.',
        ];
    }
}
