<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entreprise extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}