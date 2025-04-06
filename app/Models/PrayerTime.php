<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrayerTime extends Model
{
    protected $fillable = [
        'date', 'location_code', 'hijri',
        'fajr', 'syuruk', 'dhuhr', 'asr', 'maghrib', 'isha'
    ];
    
    /**
     * Check if any prayer time exists for a given zone, month and year
     */
    public static function hasDataForMonth(string $zoneCode, int $month, int $year): bool
    {
        return self::where('location_code', $zoneCode)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->exists();
    }
}
