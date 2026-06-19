<?php

namespace App\Http\Controllers;

use App\Models\PrayerTime;
use App\Models\PrayerZone;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DataHealthController extends Controller
{
    public function index(Request $request)
    {
        $selectedYear = (int) $request->input('year', Carbon::now()->year);

        $zones = PrayerZone::query()
            ->select('jakim_code', 'negeri', 'daerah')
            ->orderBy('negeri')
            ->orderBy('daerah')
            ->get();

        $availableZoneCodesByMonth = PrayerTime::query()
            ->selectRaw('MONTH(date) as month_number, location_code')
            ->whereYear('date', $selectedYear)
            ->distinct()
            ->get()
            ->groupBy('month_number')
            ->map(fn ($rows) => $rows->pluck('location_code')->all());

        $months = collect(range(1, 12))->map(function (int $monthNumber) use ($selectedYear, $zones, $availableZoneCodesByMonth) {
            $availableZoneCodes = collect($availableZoneCodesByMonth->get($monthNumber, []));
            $hasNoDataForMonth = $availableZoneCodes->isEmpty();

            // PHG07 was added after 2024, so older datasets should not be marked incomplete for missing it.
            if (in_array($selectedYear, [2023, 2024], true)) {
                $availableZoneCodes->push('PHG07');
            }

            $missingZones = $zones->filter(
                fn (PrayerZone $zone) => !$availableZoneCodes->contains($zone->jakim_code)
            );

            $missingCoverage = $missingZones
                ->groupBy('negeri')
                ->map(fn ($zones, string $state) => [
                    'state' => $state,
                    'zones' => $zones->pluck('jakim_code')->values()->all(),
                ])
                ->values()
                ->all();

            return [
                'monthNumber' => $monthNumber,
                'monthName' => Carbon::create($selectedYear, $monthNumber, 1)->format('F Y'),
                'status' => match (true) {
                    $hasNoDataForMonth => 'unavailable',
                    $missingZones->isEmpty() => 'available',
                    default => 'incomplete',
                },
                'missingCoverage' => $missingCoverage,
            ];
        });

        return view('data-health', compact('selectedYear', 'months'));
    }
}
