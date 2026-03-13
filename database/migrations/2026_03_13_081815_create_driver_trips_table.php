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
        Schema::create('driver_trips', function (Blueprint $table) {
              $table->id();

            $table->foreignId('driver_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Itinéraire
            $table->string('departure_city', 100);
            $table->string('arrival_city', 100);
            $table->string('departure_address', 255)->nullable();
            $table->string('arrival_address', 255)->nullable();
            $table->decimal('departure_lat', 10, 7)->nullable();
            $table->decimal('departure_lng', 10, 7)->nullable();
            $table->decimal('arrival_lat',   10, 7)->nullable();
            $table->decimal('arrival_lng',   10, 7)->nullable();

            // Horaire
            $table->date('departure_date');
            $table->time('departure_time');

            // Places & prix
            $table->tinyInteger('seats_total')->default(3);
            $table->tinyInteger('seats_available')->default(3);
            $table->unsignedInteger('price_per_seat');  // FCFA

            // Options
            $table->boolean('luggage_allowed')->default(true);
            $table->boolean('pets_allowed')->default(false);
            $table->boolean('silent_ride')->default(false);
            $table->boolean('female_only')->default(false);
            $table->text('description')->nullable();

            // Statut : scheduled / ongoing / completed / cancelled
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])
                  ->default('scheduled');

            $table->timestamps();

            $table->index(['driver_id', 'status']);
            $table->index(['departure_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_trips');
    }
};
