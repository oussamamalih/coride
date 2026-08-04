<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trajet extends Model
{
    use HasFactory;

    protected $fillable = [
        'conducteur_id',
        'ville_depart',
        'ville_arrivee',
        'horaire',
        'places_disponibles',
        'jours_recurrence',
    ];

    /**
     * Le trajet appartient à un unique conducteur (User).
     */
    public function conducteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'conducteur_id');
    }

    /**
     * Le trajet peut recevoir plusieurs réservations.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'trajet_id');
    }

    /**
     * Helper métier : Nombre de réservations confirmées sur ce trajet.
     */
    public function reservationsConfirmeesCount(): int
    {
        return $this->reservations()->where('statut', 'confirmee')->count();
    }

    /**
     * Helper métier : Vérifie s'il reste des places libres.
     */
    public function hasAvailableSeats(): bool
    {
        return $this->reservationsConfirmeesCount() < $this->places_disponibles;
    }
}
