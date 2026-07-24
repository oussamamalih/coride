<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ajouter la FK entreprise_id sur users APRÈS que entreprises existe
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('entreprise_id')
                  ->references('id')
                  ->on('entreprises')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['entreprise_id']);
        });
    }
};
