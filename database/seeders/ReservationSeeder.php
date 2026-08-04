<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Trajet;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = database_path('seeders/data/reservations.csv');

        if (!file_exists($filePath)) {
            $this->command->error("Fichier CSV introuvable : {$filePath}");
            return;
        }

        $file = fopen($filePath, 'r');
        $header = fgetcsv($file);

        while (($row = fgetcsv($file)) !== false) {
            if (count($row) < count($header)) {
                continue;
            }

            $data = array_combine($header, $row);

            $trajetId = (int) trim($data['trajet_id'] ?? $data['trajetid']);
            $passagerId = (int) trim($data['passager_id'] ?? $data['passagerid']);

            $rawStatut = trim($data['statut']);
            $statut = match ($rawStatut) {
                'enattente' => 'en_attente',
                default => $rawStatut,
            };

            if (!Trajet::find($trajetId) || !User::find($passagerId)) {
                continue;
            }

            Reservation::firstOrCreate(
                [
                    'trajet_id' => $trajetId,
                    'passager_id' => $passagerId,
                ],
                [
                    'statut' => $statut,
                    'resultat_ia' => json_encode([
                        'score' => 88,
                        'justification' => 'Trajet très compatible : même ville de départ et horaire optimal.',
                        'horaire_suggere' => '07:35:00',
                    ]),
                    'created_at' => trim($data['date_reservation'] ?? $data['datereservation'] ?? now()),
                ]
            );
        }

        fclose($file);
        $this->command->info('✅ 35 Réservations importées depuis le CSV !');
    }
}
