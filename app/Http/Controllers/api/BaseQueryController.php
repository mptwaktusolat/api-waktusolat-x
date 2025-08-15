<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\PrayerTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

        if ($prayerTimes->isEmpty()) {
            throw new \Exception("No data found for zone: {$zone} for {$durationContext->format('M/Y')}");
        }

        return $prayerTimes;
    }

    /**
     * Determine prayer zone from the given WGS84 coordinates
     *
     * @param  float  $lat  The latitude coordinate.
     * @param  float  $long  The longitude coordinate.
     * @return array An array containing the "zone", "state", and "district".
     *
     * @throws \Exception
     */
    public function detectZoneFromCoordinate(float $lat, float $long)
    {
        // Create WKT point from given parameters
        $pointWkt = sprintf('POINT(%f %f)', $long, $lat);

        $result = DB::table('zone_polygons')
            ->whereRaw(
                "ST_Within(ST_SRID(ST_GeomFromText(?), 4326), polygon)",
                [$pointWkt]
            )
            ->first();

        if (empty($result)) {
            throw new \Exception('No zone found for the given coordinates.');
        }

        return [
            'zone' => $result->jakim_code,
            'state' => $result->state,
            'district' => $result->name,
        ];
    }
}
