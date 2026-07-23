<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TrajetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
{
    return [
        'ville_depart' => 'required|string|max:255',
        'ville_arrivee' => 'required|string|max:255',
        'horaire' => 'required|date',
        'places_disponibles' => 'required|integer|min:1',
        'jours_recurrence' => 'nullable|string|max:255',
    ];
}
}
