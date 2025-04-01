<?php

namespace App\Http\Controllers\api\v2;

use App\Http\Controllers\Controller;
use App\Models\PrayerTime;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PrayerTimeContoller extends Controller
{
    private function parseToTimestamp(string $date, string $time): int
    {
        return Carbon::parse("$date $time", 'Asia/Kuala_Lumpur')->timestamp;
    }

    public function index(string $zone, Request $request) {
        $zone = strtoupper($zone);
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $prayerTimes = PrayerTime::where('location_code', $zone)
            ->whereDate('date', '>=', "$year-$month-01")
            ->whereDate('date', '<=', "$year-$month-" . date('t', strtotime("$year-$month-01")))
            ->select('date', 'hijri', 'fajr', 'syuruk', 'dhuhr', 'asr', 'maghrib', 'isha')
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($prayerTime) {
                // Do processing to the Date & Time
                // and make sure the arrangement is in this order
                return [
                    'day' => Carbon::parse($prayerTime->date)->day,
                    'date' => $prayerTime->date,
                    'hijri' => $prayerTime->hijri,
                    'fajr' => $this->parseToTimestamp($prayerTime->date, $prayerTime->fajr),
                    'syuruk' => $this->parseToTimestamp($prayerTime->date, $prayerTime->syuruk),
                    'dhuhr' => $this->parseToTimestamp($prayerTime->date, $prayerTime->dhuhr),
                    'asr' => $this->parseToTimestamp($prayerTime->date, $prayerTime->asr),
                    'maghrib' => $this->parseToTimestamp($prayerTime->date, $prayerTime->maghrib),
                    'isha' => $this->parseToTimestamp($prayerTime->date, $prayerTime->isha),
                ];
            });

        $data = [
            "zone" => $zone,
            "year" => (int) $year,
            "month" => $month,
            "month_number" => (int) $month,
            "prayers" => $prayerTimes
        ];

        return response()->json($data);
    }
}
