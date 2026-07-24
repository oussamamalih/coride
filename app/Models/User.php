<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'entreprise_id',
        'ville_residence',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // ─── Relations ───────────────────────────────────────────────────────────

    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class, 'entreprise_id');
    }

    public function trajets(): HasMany
    {
        return $this->hasMany(Trajet::class, 'conducteur_id');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'passager_id');
    }

    // ─── Role Helpers ────────────────────────────────────────────────────────

    public function estConducteur(): bool
    {
        return in_array($this->role, ['conducteur', 'les_deux']);
    }

    public function estPassager(): bool
    {
        return in_array($this->role, ['passager', 'les_deux']);
    }

    public function getRoleLibelleAttribute(): string
    {
        return match ($this->role) {
            'conducteur' => 'Conducteur',
            'passager'   => 'Passager',
            'les_deux'   => 'Conducteur & Passager',
            default      => $this->role,
        };
    }

    public function getInitialesAttribute(): string
    {
        $parts = explode(' ', $this->name);
        return strtoupper(($parts[0][0] ?? '') . (end($parts)[0] ?? ''));
    }
}
