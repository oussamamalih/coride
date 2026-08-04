<?php

namespace Database\Seeders;

use App\Models\Entreprise;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = database_path('seeders/data/employes.csv');

        if (!file_exists($filePath)) {
            $this->command->error("Fichier CSV introuvable : {$filePath}");
            return;
        }

        $file = fopen($filePath, 'r');
        $header = fgetcsv($file); // Lire la 1ère ligne (en-tête) : id,nom,email,entreprise,ville_residence,role

        $defaultPassword = Hash::make('password');

        while (($row = fgetcsv($file)) !== false) {
            if (count($row) < count($header)) {
                continue;
            }

            $data = array_combine($header, $row);

            // 1. Retrouver l'ID de l'entreprise associée
            $entreprise = Entreprise::where('nom', trim($data['entreprise']))->first();

            // 2. Insérer ou mettre à jour l'employé
            User::firstOrCreate(
                ['email' => trim($data['email'])],
                [
                    'entreprise_id' => $entreprise?->id,
                    'name' => trim($data['nom']),
                    'password' => $defaultPassword, // Mot de passe haché pré-calculé
                    'ville_residence' => trim($data['ville_residence']),
                    'role' => trim($data['role']),
                ]
            );
        }

        fclose($file);
        $this->command->info('✅ 40 Employés importés depuis le CSV !');
    }
}
