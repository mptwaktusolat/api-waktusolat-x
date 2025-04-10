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
        Schema::create('prayer_times', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('location_code');
            $table->string('hijri')->nullable();
            $table->time('fajr')->nullable();
            $table->time('syuruk')->nullable();
            $table->time('dhuhr')->nullable();
            $table->time('asr')->nullable();
            $table->time('maghrib')->nullable();
            $table->time('isha')->nullable();
            $table->timestamps();

            $table->index(['date', 'location_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prayer_times');
    }
};
