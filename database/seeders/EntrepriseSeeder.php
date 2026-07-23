<?php

namespace Database\Seeders;

use App\Models\Entreprise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntrepriseSeeder extends Seeder
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

        $noms = [];

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);
            $noms[$data['entreprise']] = true;
        }

        fclose($handle);

        foreach (array_keys($noms) as $nom) {
            Entreprise::firstOrCreate(['nom' => $nom]);
        }
    }
}
