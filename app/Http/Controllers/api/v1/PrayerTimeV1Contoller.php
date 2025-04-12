<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\api\BaseQueryController;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * @group SOLAT V1
 *
 * Return prayer time response similar to the JAKIM's structure. Suitable for drop in replacement
 * from JAKIM's API.
 *
 * *Note: The values for 'bearing' and 'lang' are empty (`""`).*
 */
class PrayerTimeV1Contoller extends BaseQueryController
{
    /**
     * Prayer Time by Month
     *
     * Returns prayer times data for a given zone, month & year.
     *
     * @urlParam zone string required The JAKIM zone code. See all zones using `/api/zones` endpoint. Example: SGR01
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchMonth(string $zone, Request $request)
    {
        $zone = strtoupper($zone);
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $prayerTimes = $this->queryPrayerTime($zone, $year, $month);
        $prayerTimes = $this->mapPrayerTimes($prayerTimes);

        $data = [
            'prayerTime' => $prayerTimes,
            'status' => 'OK!',
            'serverTime' => Carbon::now('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s'),
            'periodType' => 'month',
            'lang' => '',
            'zone' => $zone,
            'bearing' => '',
        ];

        return response()->json($data);
    }

    /**
     * Prayer Time by Day
     *
     * Returns prayer times data for a given day, zone, month & year.
     *
     * @urlParam zone string required The JAKIM zone code. See all zones using `/api/zones` endpoint. Example: SGR01
     * @urlParam day int required Tne day of the month. Example: 1
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchDay(string $zone, int $day, Request $request)
    {
        $zone = strtoupper($zone);
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $prayerTimes = $this->queryPrayerTime($zone, $year, $month);
        $prayerTimes = $this->mapPrayerTimes($prayerTimes)[$day - 1];

        $data = [
            'prayerTime' => $prayerTimes,
            'status' => 'OK!',
            'serverTime' => Carbon::now('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s'),
            'periodType' => 'day',
            'lang' => '',
            'zone' => $zone,
            'bearing' => '',
        ];

        return response()->json($data);
    }

    private function mapPrayerTimes($prayerTimes)
    {
        return $prayerTimes->map(function ($prayerTime) {
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
    }

    /**
     * Format time like JAKIM's API (eg "06:06:00")
     */
    private function formatTime(string $date, string $time): string
    {
        return Carbon::parse("$date $time", 'Asia/Kuala_Lumpur')->format('H:i:s');
    }
}
