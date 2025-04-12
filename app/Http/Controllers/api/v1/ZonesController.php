<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\api\BaseQueryController;
use App\Models\PrayerZone;
use Exception;

/**
 * @group ZONES
 *
 * Get prayer time Zones & Location information.
 */
class ZonesController extends BaseQueryController
{
    /**
     * All Zones
     *
     * Return all zones information based on JAKIM Zones. See zones visually at https://peta.waktusolat.app/
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $data = PrayerZone::select('jakim_code as jakimCode', 'negeri', 'daerah')->get();

        return response()->json($data);
    }

    /**
     * All Zones for the given state
     *
     * Return all zones information based on JAKIM Zones from the given state (Negeri) initial.
     *
     * @urlParam state string required The state (negeri) initial. Eg: `prk` for Perak, `sbh` for Sabah etc.). Example: prk
     */
    public function getByState(string $state)
    {
        $data = PrayerZone::select('jakim_code as jakimCode', 'negeri', 'daerah')
            ->where('jakim_code', 'like', "{$state}%")
            ->get();

        return response()->json($data);
    }

    /**
     * Get zone by GPS coordinates.
     *
     * Return the zone information for the given WGS84 coordinates.
     *
     * @urlParam lat number required The latitude coordinate. Example: 3.068498
     * @urlParam long number required The longitude coordinate. Example: 101.630263
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getZoneFromCoordinate(float $lat, float $lng)
    {
        try {
            $zoneObject = $this->detectZoneFromCoordindate($lat, $lng);

            return response()->json($zoneObject);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
