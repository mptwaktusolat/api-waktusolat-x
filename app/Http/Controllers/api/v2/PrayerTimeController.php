<?php

namespace App\Http\Controllers\api\v2;

use App\Http\Controllers\api\BaseQueryController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * @group SOLAT V2
 *
 * Get prayer times data for a given zone. Updated endpoint.
 */
class PrayerTimeController extends BaseQueryController
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
     * @response status=404 scenario="Data not found" {"message": "No data found for zone: XXXXX for MMM/YYYY"}
     * @response status=500 scenario="Internal server error." {"message": "Server error"}
     */
    public function fetchMonth(string $zone, Request $request)
    {
        // Query parameters
        $request->validate([
            'year' => 'integer|digits:4|min:2020',
            'month' => 'integer|min:1',
        ]);

        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $zone = strtoupper($zone);

        try {
            $prayerTimes = $this->queryPrayerTime($zone, $year, $month);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 404);
        }

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
     * @urlParam lat number required The latitude coordinate. Example: 3.068498
     * @urlParam long number required The longitude coordinate. Example: 101.630263
     *
     * @queryParam year int The year. Defaults to current year. Example: 2025
     * @queryParam month int The month number. 1 => January, 2 => February etc. Defaults to current month. Example: 6
     *
     * @response status=404 scenario="Data not found" {"message": "No data found for zone: XXXXX for MMM/YYYY"}
     * @response status=500 scenario="Internal server error." {"message": "Server error"}
     * @response status=422 scenario="Invalid parameters." {"message": "Invalid coordinates. lat must be a number. long must be a number."}
     */
    public function fetchMonthLocationByGps($lat, $long, Request $request)
    {
        // Query parameters
        $request->validate([
            'year' => 'integer|digits:4|min:2020',
            'month' => 'integer|min:1',
        ]);

        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $coordValidator = validator(
            ['lat' => $lat, 'long' => $long],
            ['lat' => 'required|numeric', 'long' => 'required|numeric'],
        );

        if ($coordValidator->fails()) {
            return response()->json([
                'message' => 'Invalid coordinates. '.implode(' ', $coordValidator->errors()->all()),
            ], 422);
        }

        // Zone detection
        try {
            $zoneObject = $this->detectZoneFromCoordinate((float) $lat, (float) $long);
            $zone = $zoneObject['zone'];
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 422);
        }

        try {
            $prayerTimes = $this->queryPrayerTime($zone, $year, $month);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 404);
        }
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
     * Deprecated: Use /v2/solat/gps/{lat}/{long} instead. This endpoint has incorrect URL format and will be removed in 2026.
     * Return the prayer times in a specific month with automatic zone detection based on GPS coordinates.
     *
     * @urlParam lat number required The latitude coordinate. Example: 3.068498
     * @urlParam long number required The longitude coordinate. Example: 101.630263
     *
     * @queryParam year int The year. Defaults to current year. Example: 2025
     * @queryParam month int The month number. 1 => January, 2 => February etc. Defaults to current month. Example: 6
     *
     * @response status=404 scenario="Data not found" {"message": "No data found for zone: XXXXX for MMM/YYYY"}
     * @response status=500 scenario="Internal server error." {"message": "Server error"}
     *
     * @deprecated
     */
    public function fetchMonthLocationByGpsDeprecated($lat, $long, Request $request)
    {
        return $this->fetchMonthLocationByGps($lat, $long, $request);
    }

    /**
     * Map prayer times to the required format
     *
     * @return Collection
     */
    private function mapPrayerTimes(Collection $prayerTimes)
    {
        return $prayerTimes->map(function ($prayerTime) {
            // Do processing to the Date & Time
            // and make sure the arrangement is in this order
            return [
                'day' => Carbon::parse($prayerTime->date)->day,
                'hijri' => $prayerTime->hijri,
                'imsak' => $this->parseToTimestamp($prayerTime->date, $prayerTime->imsak),
                'fajr' => $this->parseToTimestamp($prayerTime->date, $prayerTime->fajr),
                'syuruk' => $this->parseToTimestamp($prayerTime->date, $prayerTime->syuruk),
                'dhuha' => $this->parseToTimestamp($prayerTime->date, $prayerTime->dhuha),
                'dhuhr' => $this->parseToTimestamp($prayerTime->date, $prayerTime->dhuhr),
                'asr' => $this->parseToTimestamp($prayerTime->date, $prayerTime->asr),
                'maghrib' => $this->parseToTimestamp($prayerTime->date, $prayerTime->maghrib),
                'isha' => $this->parseToTimestamp($prayerTime->date, $prayerTime->isha),
            ];
        });
    }

    /**
     * Parse date and time to timestamp
     *
     * @param  string  $date  The date string
     * @param  ?string  $time  The time string
     * @return int|null
     */
    private function parseToTimestamp(string $date, ?string $time): ?int
    {
        // Some zones have null Dhuha time, especially year 2025 and before.
        if (empty($time)) {
            return null;
        }

        return Carbon::parse("$date $time", 'Asia/Kuala_Lumpur')->timestamp;
    }
}
