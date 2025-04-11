<?php

namespace App\Http\Controllers\api\v2;

use App\Http\Controllers\api\BasePrayerTimeController;
use App\Http\Controllers\Controller;
use App\Models\PrayerTime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * @group SOLAT V2
 *
 * Get prayer times data for a given zone. Updated endpoint.
 */
class PrayerTimeContoller extends BasePrayerTimeController
{
    /**
     * v2/Prayer Time
     *
     * Return the prayer times in a specific month for a given zone.
     *
     * @urlParam zone string required The JAKIM zone code. See all zones using `/api/zones` endpoint. Example: SGR01
     *
     * @queryParam year int The year. Defaults to current year. Example: 2025
     * @queryParam month int The month number. 1 => January, 2 => February etc. Defaults to current month. Example: 6
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
            'zone' => $zone,
            'year' => (int) $year,
            'month' => strtoupper(Carbon::createFromDate($year, $month, 1)->format('M')),
            'month_number' => (int) $month,
            'last_updated' => null,
            'prayers' => $prayerTimes,
        ];

        return response()->json($data);
    }

    /**
     * v2/Prayer Time by GPS
     *
     * Return the prayer times in a specific month with automatic zone detection based on GPS coordinates.
     *
     * @urlParam lat number required The latitude coordinate. Example: 3.139003
     * @urlParam long number required The longitude coordinate. Example: 101.686855
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function fetchMonthLocationByGps(float $lat, float $long, Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $response = Http::get("http://localhost:5166/location/{$lat}/{$long}");

        // Stop if the response is not successful
        if ($response->failed()) {
            return response()->json([
                'error' => 'Unable to fetch location data',
            ], 500);
        }

        $zone = $response->json()['zone'];
        $prayerTimes = $this->queryPrayerTime($zone, $year, $month);
        $prayerTimes = $this->mapPrayerTimes($prayerTimes);

        $data = [
            'zone' => $zone,
            'year' => (int) $year,
            'month' => strtoupper(Carbon::createFromDate($year, $month, 1)->format('M')),
            'month_number' => (int) $month,
            'last_updated' => null,
            'prayers' => $prayerTimes,
        ];

        return response()->json($data);
    }

    private function parseToTimestamp(string $date, string $time): int
    {
        return Carbon::parse("$date $time", 'Asia/Kuala_Lumpur')->timestamp;
    }

    /**
     * Map prayer times to the required format
     *
     * @param \Illuminate\Support\Collection $prayerTimes
     * @return \Illuminate\Support\Collection
     */
    private function mapPrayerTimes($prayerTimes)
    {
        return $prayerTimes->map(function ($prayerTime) {
            // Do processing to the Date & Time
            // and make sure the arrangement is in this order
            return [
                'day' => Carbon::parse($prayerTime->date)->day,
                'hijri' => $prayerTime->hijri,
                'fajr' => $this->parseToTimestamp($prayerTime->date, $prayerTime->fajr),
                'syuruk' => $this->parseToTimestamp($prayerTime->date, $prayerTime->syuruk),
                'dhuhr' => $this->parseToTimestamp($prayerTime->date, $prayerTime->dhuhr),
                'asr' => $this->parseToTimestamp($prayerTime->date, $prayerTime->asr),
                'maghrib' => $this->parseToTimestamp($prayerTime->date, $prayerTime->maghrib),
                'isha' => $this->parseToTimestamp($prayerTime->date, $prayerTime->isha),
            ];
        });
    }
}
