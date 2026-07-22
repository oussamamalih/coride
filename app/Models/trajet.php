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

    protected $casts = [
        'horaire' => 'datetime',
    ];

    public function conducteur()
    {
        return $this->belongsTo(User::class, 'conducteur_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}