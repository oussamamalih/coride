<?php

namespace Database\Seeders;

use App\Models\Reservation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = __DIR__.'/data/reservations.csv';

        if (! file_exists($path)) {
            return;
        }

        $handle = fopen($path, 'r');
        $header = fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);

            $reservation = Reservation::firstOrNew(['id' => (int) $data['id']]);
            $reservation->id = (int) $data['id'];
            $reservation->trajet_id = (int) $data['trajet_id'];
            $reservation->passager_id = (int) $data['passager_id'];
            $reservation->statut = $data['statut'];
            $reservation->date_reservation = $data['date_reservation'];
            $reservation->save();
        }

        fclose($handle);
    }
}
