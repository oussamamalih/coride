<?php

namespace App\Models;

use App\Casts\ScoreCompatibiliteCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'trajet_id',
        'passager_id',
        'statut',
        'resultat_ia',
    ];

    /**
     * 'resultat_ia' est stocké en JSON en base et désérialisé automatiquement
     * en objet ScoreCompatibilite (score, justification, horaire_suggere, ...).
     */
    protected $casts = [
        'resultat_ia' => ScoreCompatibiliteCast::class,
    ];

    /**
     * La réservation concerne un trajet précis.
     */
    public function trajet(): BelongsTo
    {
        return $this->belongsTo(Trajet::class, 'trajet_id');
    }

    /**
     * La réservation a été faite par un passager (User).
     */
    public function passager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'passager_id');
    }
}
