<?php

namespace Database\Seeders;

use App\Models\Entreprise;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = __DIR__.'/data/employes.csv';

        if (! file_exists($path)) {
            return;
        }

        $handle = fopen($path, 'r');
        $header = fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);

            $entreprise = Entreprise::firstOrCreate(['nom' => $data['entreprise']]);

            // Le CSV utilise "les deux", l'enum en base attend "les_deux"
            $role = str_replace(' ', '_', trim($data['role']));

            // 'id' n'est pas fillable (protection mass-assignment) : on l'assigne
            // directement pour que les id des employes.csv soient conserves,
            // ce dont trajets.csv / reservations.csv ont besoin pour les references.
            $user = User::firstOrNew(['email' => $data['email']]);
            $user->id = (int) $data['id'];
            $user->nom = $data['nom'];
            $user->entreprise_id = $entreprise->id;
            $user->ville_residence = $data['ville_residence'];
            $user->role = $role;
            $user->password = Hash::make('password');
            $user->email_verified_at = now();
            $user->save();
        }

        fclose($handle);
    }
}
