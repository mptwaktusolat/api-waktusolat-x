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
        Schema::table('zone_polygons', function (Blueprint $table) {
            // remove spatial index before altering SRID
            $table->dropSpatialIndex(['polygon']);
            $table->geometry('polygon')->srid(4326)->change();
            // re-add spatial index after altering SRID
            $table->spatialIndex('polygon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zone_polygons', function (Blueprint $table) {
            $table->dropSpatialIndex(['polygon']);
            $table->geometry('polygon')->srid(0)->change();
            $table->spatialIndex('polygon');
        });
    }
};
