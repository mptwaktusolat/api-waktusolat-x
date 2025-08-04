<?php

namespace App\Http\Controllers;

use App\Models\PrayerZone;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DataHealthController extends Controller
{
    public function index(Request $request)
    {
        $zones = PrayerZone::all();

        // Get selected zone and year from request or use defaults
        $selectedZone = $request->input('zone', $zones->first()->code);
        $selectedYear = (int) $request->input('year', Carbon::now()->year);

        // Return the Inertia data-health page with all necessary data
        return view('data-health', compact('zones', 'selectedZone', 'selectedYear'));
    }
}
