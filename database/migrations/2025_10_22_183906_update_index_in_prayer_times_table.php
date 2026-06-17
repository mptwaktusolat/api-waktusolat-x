<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration optimizes the prayer_times table index to match the actual query pattern.
     * The original index ['date', 'location_code'] was not optimal for queries that filter
     * by location_code first.
     */
    public function up(): void
    {
        Schema::table('prayer_times', function (Blueprint $table) {
            $table->dropIndex(['date', 'location_code']);
            $table->unique(['location_code', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prayer_times', function (Blueprint $table) {
            $table->dropUnique(['location_code', 'date']);
            $table->index(['date', 'location_code']);
        });
    }
};
