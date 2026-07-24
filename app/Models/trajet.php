<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Trajet extends Model
{
    protected $fillable = [
        'conducteur_id',
        'ville_depart',
        'ville_arrivee',
        'horaire',
        'places_disponibles',
        'jours_recurrence',
    ];

    protected $casts = [
        'places_disponibles' => 'integer',
    ];

    // ─── Relations ───────────────────────────────────────────────────────────

    public function conducteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'conducteur_id');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'trajet_id');
    }

    public function reservationsConfirmees(): HasMany
    {
        return $this->hasMany(Reservation::class, 'trajet_id')
            ->where('statut', 'confirmee');
    }

    public function passagers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'reservations', 'trajet_id', 'passager_id')
            ->withPivot(['statut', 'date_reservation', 'score_compatibilite'])
            ->withTimestamps();
    }

    // ─── Accessors ───────────────────────────────────────────────────────────

    public function getPlacesRestantesAttribute(): int
    {
        return $this->places_disponibles - $this->reservationsConfirmees()->count();
    }

    public function getEstCompletAttribute(): bool
    {
        return $this->placesRestantes <= 0;
    }

    public function getHoraireFormateAttribute(): string
    {
        return substr($this->horaire, 0, 5);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function aDesReservationsConfirmees(): bool
    {
        return $this->reservationsConfirmees()->exists();
    }
}
