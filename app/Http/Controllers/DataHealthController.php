<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\PrayerZone;
use App\Models\PrayerTime;
use Carbon\Carbon;

class DataHealthController extends Controller
{
    public function index(Request $request) {
        $zones = PrayerZone::all();
        
        // Get selected zone and year from request or use defaults
        $selectedZone = $request->input('zone', $zones->first()->code ?? 'WLY01');
        $selectedYear = (int) $request->input('year', Carbon::now()->year);
        
        // Check data availability for each month
        $months = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        
        $monthsAvailability = [];
        
        foreach ($months as $index => $monthName) {
            $monthNumber = $index + 1;
            $isAvailable = PrayerTime::hasDataForMonth($selectedZone, $monthNumber, $selectedYear);
            
            $monthsAvailability[] = [
                'month' => $monthName,
                'monthNumber' => $monthNumber,
                'isAvailable' => $isAvailable,
            ];
        }
        
        // Return the Inertia data-health page with all necessary data
        return Inertia::render('data-health', [
            'zones' => $zones,
            'selectedZone' => $selectedZone,
            'selectedYear' => $selectedYear,
            'monthsAvailability' => $monthsAvailability,
        ]);
    }
}
