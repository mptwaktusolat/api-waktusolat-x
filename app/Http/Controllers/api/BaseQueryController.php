<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\PrayerTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class BaseQueryController extends Controller
{
    /**
     * Query Prayer Time from the database
     *
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

    /**
     * Determine prayer zone from the given WGS84 coordinates by calling the
     * geojson-helper server.
     *
     * @param  float  $lat  The latitude coordinate.
     * @param  float  $long  The longitude coordinate.
     * @return array An array containing the "zone", "state", and "district".
     *
     * @throws \Exception
     */
    public function detectZoneFromCoordindate(float $lat, float $long)
    {
        $response = Http::get("http://localhost:5166/location/{$lat}/{$long}");

        if ($response->failed()) {
            if ($response->status() === 404) {
                $responseMsg = $response->json()['error'];

                throw new \Exception($responseMsg);
            }

            throw new \Exception('GeoHelper Server error');
        }

        return $response->json();
    }
}
