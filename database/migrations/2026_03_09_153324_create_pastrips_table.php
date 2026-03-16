<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pastrips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Itinéraire
            $table->string('departure_city');
            $table->string('arrival_city');
            $table->string('departure_address')->nullable();
            $table->string('arrival_address')->nullable();

            // Coordonnées GPS (Nominatim / Leaflet)
            $table->decimal('departure_lat', 10, 7)->nullable();
            $table->decimal('departure_lng', 10, 7)->nullable();
            $table->decimal('arrival_lat',   10, 7)->nullable();
            $table->decimal('arrival_lng',   10, 7)->nullable();

            // Date & heure
            $table->date('requested_date');
            $table->time('requested_time');
            $table->unsignedTinyInteger('flexibility')->default(30); // en minutes

            // Passagers & budget
            $table->unsignedTinyInteger('passengers')->default(1);
            $table->unsignedInteger('budget_max')->nullable(); // en FCFA

            // Préférences
            $table->boolean('need_luggage_space')->default(false);
            $table->boolean('female_driver_only')->default(false);
            $table->boolean('pets_with_me')->default(false);
            $table->boolean('silent_ride')->default(false);

            // Message
            $table->text('message')->nullable();

            // Expiration
            $table->unsignedTinyInteger('expires_in_hours')->default(3);
            $table->timestamp('expires_at')->nullable();

            // Statut
            $table->enum('status', ['pending', 'accepted', 'cancelled', 'expired'])->default('pending');

            // Conducteur qui a accepté
            $table->foreignId('accepted_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pastrips');
    }
};
