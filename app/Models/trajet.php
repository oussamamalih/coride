<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    // Relation avec le conducteur
    public function conducteur()
    {
        return $this->belongsTo(User::class, 'conducteur_id');
    }

    // Relation avec les réservations
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // Réservations confirmées
    public function reservationsConfirmees()
    {
        return $this->reservations()
            ->where('statut', 'confirmee');
    }
     protected function casts(): array
    {
    return [
        'horaire' => 'datetime',
    ];
    }
    // Calcul des places restantes
    public function placesRestantes()
    {
        return $this->places_disponibles
            - $this->reservationsConfirmees()->count();
    }
}