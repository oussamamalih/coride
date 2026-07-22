<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trajets', function (Blueprint $table) {

            $table->id();

            $table->foreignId('conducteur_id')
                    ->constrained('users')
                    ->cascadeOnDelete();

            $table->string('ville_depart');

            $table->string('ville_arrivee');

            $table->dateTime('horaire');

            $table->integer('places_disponibles');

            $table->string('jours_recurrence')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trajets');
    }
};