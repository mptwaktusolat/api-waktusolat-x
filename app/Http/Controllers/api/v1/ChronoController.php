<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

/**
 * @group CHRONO
 *
 * Get current date & time information.
 */
class ChronoController extends Controller
{
    /**
     * Get Server time
     *
     * Return the current server date and time for Malaysia (GMT+8) timezone.
     */
    public function index()
    {
        $now = Carbon::now('Asia/Kuala_Lumpur');

        $data = [
            'date' => $now->format('d-m-Y'),
            'day_of_month' => $now->day,
            'day_of_week' => $now->dayOfWeekIso,
            'time12' => $now->format('h:i:s A'),
            'time24' => $now->format('H:i:s'),
            'unix' => $now->timestamp,
            'iso8601' => $now->toIso8601String(),
            'timezone' => 'Asia/Kuala_Lumpur',
        ];

        return response()->json($data);
    }
}
