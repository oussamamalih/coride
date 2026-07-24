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
            $table->foreignId('trajet_id')->constrained('trajets')->restrictOnDelete();
            $table->foreignId('passager_id')->constrained('users')->cascadeOnDelete();
            $table->enum('statut', ['en_attente', 'confirmee', 'refusee', 'annulee'])->default('en_attente');
            $table->date('date_reservation');
            $table->text('score_compatibilite')->nullable(); // Cast JSON IA
            $table->timestamps();

            // Un passager ne peut pas réserver deux fois le même trajet
            $table->unique(['trajet_id', 'passager_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
