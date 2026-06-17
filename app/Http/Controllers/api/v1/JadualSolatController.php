<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\api\BaseQueryController;
use App\Models\PrayerZone;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;

/**
 * @group JADUAL SOLAT
 *
 * Download monthly prayer timetable in PDF.
 */
class JadualSolatController extends BaseQueryController
{
    /**
     * Download monthly prayer timetable in PDF.
     *
     * Return the prayer times in a specific month for a given zone.
     *
     * @urlParam zone string required The JAKIM zone code. See all zones using `/api/zones` endpoint. Example: SGR01
     *
     * @queryParam year int The year. Defaults to current year. Example: 2026
     * @queryParam month int The month number. 1 => January, 2 => February etc. Defaults to current month. Example: 8
     *
     * @response <<binary>> The PDF file
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

        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));

        // Didn't use the default argument because I want to handle when the param is empty as well
        $orientation = $request->get('orientation') ?? 'landscape';
        $timeFormat = match ($request->get('timeFormat')) {
            '12h' => 'h:i A',
            default => 'H:i',
        };

        $zone = strtoupper($zone);

        try {
            $prayerTimes = $this->queryPrayerTime($zone, $year, $month);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 404);
        }

        $prayerTimes = $this->mapPrayerTimes($prayerTimes, $timeFormat);

        $title = 'Jadual Waktu Solat';
        $zoneDetails = PrayerZone::where('jakim_code', $zone)->first();

        $view = view('jadual_solat.jadual_solat', compact('zoneDetails', 'title', 'month', 'year', 'orientation', 'prayerTimes'));

        $dompdf = new Dompdf();
        $dompdf->setPaper('A4', $orientation);

        // Set smaller margins using Dompdf options
        $options = $dompdf->getOptions();
        $options->set('defaultPaperSize', 'A4');
        $options->set('isHtml5ParserEnabled', true);
        $dompdf->setOptions($options);

        $dompdf->loadHtml($view->render());
        $dompdf->render();

        // The MyCors middleware wouldn't work in this route, so we're adding it manually here.
        header("Access-Control-Allow-Origin: *");
        $dompdf->stream('jadual_solat.pdf', ['Attachment' => false]);
    }

    /**
     * Format time to the given format
     */
    private function formatTime(string $date, string $time, string $timeFormat): string
    {
        return Carbon::parse("$date $time", 'Asia/Kuala_Lumpur')->format($timeFormat);
    }

    /**
     * Map prayer times to the given format
     *
     * @param \Illuminate\Support\Collection $prayerTimes
     * @return \Illuminate\Support\Collection
     */
    private function mapPrayerTimes(\Illuminate\Support\Collection $prayerTimes, string $timeFormat = 'H:i')
    {
        return $prayerTimes->map(function ($prayerTime) use ($timeFormat) {
            // Do processing to the Date & Time
            // and make sure the arrangement is in this order
            return [
                'date' => Carbon::parse($prayerTime->date)->format('d-m-Y'),
                'hijri' => $prayerTime->hijri,
                'fajr' => $this->formatTime($prayerTime->date, $prayerTime->fajr, $timeFormat),
                'syuruk' => $this->formatTime($prayerTime->date, $prayerTime->syuruk, $timeFormat),
                'dhuhr' => $this->formatTime($prayerTime->date, $prayerTime->dhuhr, $timeFormat),
                'asr' => $this->formatTime($prayerTime->date, $prayerTime->asr, $timeFormat),
                'maghrib' => $this->formatTime($prayerTime->date, $prayerTime->maghrib, $timeFormat),
                'isha' => $this->formatTime($prayerTime->date, $prayerTime->isha, $timeFormat),
            ];
        });
    }
}
