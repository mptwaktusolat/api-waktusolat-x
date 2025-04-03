<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\PrayerTime;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Return prayer time response just like JAKIM's one. Suitable for drop in replacement
 * for JAKIM's API.
 * @see https://www.e-solat.gov.my/
 * Note: The values for 'bearing' and 'lang' will be empty strings.
 */
class PrayerTimeV1Contoller extends Controller
{
    /**
     * Format time like JAKIM's API (eg "06:06:00")
     * @param string $date
     * @param string $time
     * @return string
     */
    private function formatTime(string $date, string $time): string
    {
        return Carbon::parse("$date $time", 'Asia/Kuala_Lumpur')->format('H:i:s');
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
                    'hijri' => $prayerTime->hijri,
                    'date' => Carbon::parse($prayerTime->date)->format('d-M-Y'),
                    'day' => Carbon::parse($prayerTime->date)->format('l'),
                    'fajr' => $this->formatTime($prayerTime->date, $prayerTime->fajr),
                    'syuruk' => $this->formatTime($prayerTime->date, $prayerTime->syuruk),
                    'dhuhr' => $this->formatTime($prayerTime->date, $prayerTime->dhuhr),
                    'asr' => $this->formatTime($prayerTime->date, $prayerTime->asr),
                    'maghrib' => $this->formatTime($prayerTime->date, $prayerTime->maghrib),
                    'isha' => $this->formatTime($prayerTime->date, $prayerTime->isha),
                ];
            });

        $data = [
            "prayerTime" => $prayerTimes,
            "status" => "OK!",
            "serverTime" => Carbon::now('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s'),
            "periodType" => "month",
            "lang" => "",
            "zone" => $zone,
            "bearing" => "",
        ];

        return response()->json($data);
    }
}
