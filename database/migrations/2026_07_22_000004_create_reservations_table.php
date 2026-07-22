<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {

            $table->id();

            $table->foreignId('trajet_id')
                    ->constrained()
                    ->cascadeOnDelete();

            $table->foreignId('passager_id')
                    ->constrained('users')
                    ->cascadeOnDelete();

            $table->enum('statut', [
                'en_attente',
                'confirmee',
                'refusee',
                'annulee'
            ])->default('en_attente');

            $table->dateTime('date_reservation');

            $table->json('resultat_ia')->nullable();

            $table->timestamps();

            $table->unique(['trajet_id', 'passager_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};