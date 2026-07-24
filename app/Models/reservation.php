<?php

namespace App\Models;

use App\Casts\ScoreCompatibiliteCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'trajet_id',
        'passager_id',
        'statut',
        'date_reservation',
        'score_compatibilite',
    ];

    protected $casts = [
        'date_reservation'   => 'date',
        'score_compatibilite' => ScoreCompatibiliteCast::class,
    ];

    // Transitions de statut autorisées
    const TRANSITIONS = [
        'en_attente' => ['confirmee', 'refusee', 'annulee'],
        'confirmee'  => ['annulee'],
        'refusee'    => [],
        'annulee'    => [],
    ];

    // ─── Relations ───────────────────────────────────────────────────────────

    public function trajet(): BelongsTo
    {
        return $this->belongsTo(Trajet::class, 'trajet_id');
    }

    public function passager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'passager_id');
    }

    // ─── Business Logic ──────────────────────────────────────────────────────

    public function peutTransitionnerVers(string $nouveauStatut): bool
    {
        return in_array($nouveauStatut, self::TRANSITIONS[$this->statut] ?? []);
    }

    public function changerStatut(string $nouveauStatut): bool
    {
        if (! $this->peutTransitionnerVers($nouveauStatut)) {
            return false;
        }

        $this->statut = $nouveauStatut;
        $this->save();

        return true;
    }

    public function getBadgeClassAttribute(): string
    {
        return match ($this->statut) {
            'confirmee'  => 'badge-confirmee',
            'refusee'    => 'badge-refusee',
            'annulee'    => 'badge-annulee',
            default      => 'badge-attente',
        };
    }

    public function getStatutLibelleAttribute(): string
    {
        return match ($this->statut) {
            'en_attente' => 'En attente',
            'confirmee'  => 'Confirmée',
            'refusee'    => 'Refusée',
            'annulee'    => 'Annulée',
            default      => $this->statut,
        };
    }

    public function aUnScore(): bool
    {
        return ! is_null($this->score_compatibilite);
    }
}
