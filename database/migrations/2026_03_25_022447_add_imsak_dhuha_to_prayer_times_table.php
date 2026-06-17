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
        Schema::table('prayer_times', function (Blueprint $table) {
            $table->time('imsak')->nullable()->after('hijri');
            $table->time('dhuha')->nullable()->after('syuruk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prayer_times', function (Blueprint $table) {
            $table->dropColumn('imsak');
            $table->dropColumn('dhuha');
        });
    }
};
