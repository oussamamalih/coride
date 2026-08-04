<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entreprise extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'domaine_email',
        'secteur_activite',
        'adresse',
    ];

    /**
     * Une entreprise emploie plusieurs salariés (Users).
     */
    public function employes(): HasMany
    {
        return $this->hasMany(User::class, 'entreprise_id');
    }
}
