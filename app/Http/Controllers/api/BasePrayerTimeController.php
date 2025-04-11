<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\PrayerTime;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BasePrayerTimeController extends Controller
{
    /**
     * Query Prayer Time from the database
     * @param string $zone
     * @param int $year
     * @param int $month
     * @return \Illuminate\Support\Collection
     */
    public function queryPrayerTime(string $zone, int $year, int $month)
    {
        $durationContext = Carbon::create($year, $month);

        $prayerTimes = PrayerTime::where('location_code', $zone)
            ->whereDate('date', '>=', $durationContext->startOfMonth()->toDateString())
            ->whereDate('date', '<=', $durationContext->endOfMonth()->toDateString())
            ->orderBy('date', 'asc')
            ->get();
            

        return $prayerTimes;
    }
}
