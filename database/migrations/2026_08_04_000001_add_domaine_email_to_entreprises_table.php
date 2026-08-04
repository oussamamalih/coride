<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajoute le nom de domaine professionnel de chaque entreprise
     * (ex: "mobilitech.com"), utilisé pour restreindre l'inscription
     * aux emails professionnels de l'entreprise sélectionnée (US5).
     */
    public function up(): void
    {
        Schema::table('entreprises', function (Blueprint $table) {
            $table->string('domaine_email')->nullable()->after('nom');
        });
    }

    public function down(): void
    {
        Schema::table('entreprises', function (Blueprint $table) {
            $table->dropColumn('domaine_email');
        });
    }
};
