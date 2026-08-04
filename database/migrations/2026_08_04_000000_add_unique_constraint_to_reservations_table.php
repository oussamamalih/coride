<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Un passager ne peut pas réserver deux fois le même trajet.
     * La règle était vérifiée uniquement côté application (ReservationController) ;
     * on l'impose désormais aussi au niveau base de données pour éviter
     * toute réservation en double en cas de requêtes concurrentes.
     */
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->unique(['trajet_id', 'passager_id'], 'reservations_trajet_passager_unique');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropUnique('reservations_trajet_passager_unique');
        });
    }
};
