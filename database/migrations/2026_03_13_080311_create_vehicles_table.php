<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('driver_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Type de véhicule
            $table->enum('type', ['moto', 'tricycle', 'voiture'])->default('voiture');

            // Infos
            $table->string('brand', 80);        // Marque
            $table->string('model', 80);        // Modèle
            $table->string('color', 50);        // Couleur
            $table->string('plate', 20);        // Immatriculation

            $table->timestamps();

            // Un conducteur = un seul véhicule
            $table->unique('driver_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
