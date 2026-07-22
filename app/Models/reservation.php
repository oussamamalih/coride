<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'trajet_id',
        'passager_id',
        'statut',
        'date_reservation',
        'resultat_ia',
    ];

    protected $casts = [
        'date_reservation' => 'datetime',
        'resultat_ia' => 'array',
    ];

    public function trajet()
    {
        return $this->belongsTo(Trajet::class);
    }

    public function passager()
    {
        return $this->belongsTo(User::class, 'passager_id');
    }
}