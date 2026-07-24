<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('nom');

            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();

            $table->foreignId('entreprise_id')
                    ->constrained()
                    ->cascadeOnDelete();

            $table->string('ville_residence');

            $table->enum('role', [
                'conducteur',
                'passager',
                'les_deux'
            ]);

            $table->string('password');

            $table->rememberToken();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};