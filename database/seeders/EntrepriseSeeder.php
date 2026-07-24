<?php

namespace Database\Seeders;

use App\Models\Entreprise;
use Illuminate\Database\Seeder;

class EntrepriseSeeder extends Seeder
{
    public function run(): void
    {
        $entreprises = [
            'MobiliTech',
            'NextBuild',
            'Atlas Digital',
            'GreenLogix',
            'Kandia Solutions',
        ];

        foreach ($entreprises as $nom) {
            Entreprise::firstOrCreate(['nom' => $nom]);
        }
    }
}
