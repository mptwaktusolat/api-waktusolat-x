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
        Schema::create('zone_polygons', function (Blueprint $table) {
            $table->id();
            $table->string('string_id')->unique();
            $table->string('name');
            $table->smallInteger('code_state');
            $table->string('state');
            $table->string('jakim_code');
            $table->geometry('polygon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zone_polygons');
    }
};
