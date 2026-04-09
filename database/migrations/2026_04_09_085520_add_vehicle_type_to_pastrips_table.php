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
        Schema::table('pastrips', function (Blueprint $table) {
            $table->enum('vehicle_type', ['tricycle', 'voiture'])
                  ->default('voiture')
                  ->after('passengers');
        });
    }

    public function down(): void
    {
        Schema::table('pastrips', function (Blueprint $table) {
            $table->dropColumn('vehicle_type');
        });
    }
};
