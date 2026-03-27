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
        Schema::table('vehicles', function (Blueprint $table) {
            // MySQL exige de supprimer la FK avant de supprimer l'index unique
            $table->dropForeign(['driver_id']);
            $table->dropUnique(['driver_id']);
            // Recréer la FK sans contrainte unique
            $table->foreign('driver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->unique('driver_id');
            $table->foreign('driver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
