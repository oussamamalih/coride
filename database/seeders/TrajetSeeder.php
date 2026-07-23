<?php

namespace Database\Seeders;

use App\Models\Trajet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TrajetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = __DIR__.'/data/trajets.csv';

        if (! file_exists($path)) {
            return;
        }

        $handle = fopen($path, 'r');
        $header = fgetcsv($handle);

        // horaire.csv ne contient qu'une heure (ex: "08:00"), le trajet etant
        // recurrent selon jours_recurrence. On l'ancre sur la date du jour
        // pour obtenir un datetime valide en base.
        $dateReference = Carbon::today()->toDateString();

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);

            $horaire = Carbon::parse($dateReference.' '.$data['horaire']);

            $trajet = Trajet::firstOrNew(['id' => (int) $data['id']]);
            $trajet->id = (int) $data['id'];
            $trajet->conducteur_id = (int) $data['conducteur_id'];
            $trajet->ville_depart = $data['ville_depart'];
            $trajet->ville_arrivee = $data['ville_arrivee'];
            $trajet->horaire = $horaire;
            $trajet->places_disponibles = (int) $data['places_disponibles'];
            $trajet->jours_recurrence = $data['jours_recurrence'] !== '' ? $data['jours_recurrence'] : null;
            $trajet->save();
        }

        fclose($handle);
    }
}
