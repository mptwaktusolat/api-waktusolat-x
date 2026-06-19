<?php

namespace App\Console\Commands\Resource;

use App\Models\PrayerZone;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use League\Csv\Writer;

class FetchWaktuSolatFromSource extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-waktu-solat-from-source {year}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Waktu Solat data from E-Solat JAKIM for a specific year and dump output to resource directory';

    private const API_URL = 'https://www.e-solat.gov.my/index.php?r=esolatApi%2Ftakwimsolat&period=duration';

    // Delay between API requests (in seconds) to avoid hitting rate limits or overloading the external service.
    private const DELAY_SECONDS = 1.2;

    // Maximum number of retries for failed API requests to improve reliability in case of transient errors.
    private const MAX_RETRIES = 3;

    // Delay (in seconds) between retries to give the external API time to recover from temporary issues.
    private const RETRY_DELAY = 2;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $year = $this->argument('year');

        // Validate year argument
        if (!$year || !is_numeric($year)) {
            $this->error('Year argument is required and must be numeric.');

            return Command::FAILURE;
        }

        $year = (int) $year;

        // Validate year range
        if ($year < 2000 || $year > 2100) {
            $this->error('Year must be between 2000 and 2100.');

            return Command::FAILURE;
        }

        $this->info("Fetching Waktu Solat data for year {$year}...");

        // Generate date range
        $dateStart = "{$year}-01-01";
        $dateEnd = "{$year}-12-31";

        $this->info("Date range: {$dateStart} to {$dateEnd}");

        // Get all zones from database
        $zones = PrayerZone::select('jakim_code', 'negeri', 'daerah')->get();

        if ($zones->isEmpty()) {
            $this->error('No prayer zones found in database.');

            return Command::FAILURE;
        }

        $this->info("Found {$zones->count()} zones to fetch.");

        $allData = [];

        foreach ($zones as $zone) {
            $zoneCode = $zone->jakim_code;

            // Fetch data for this zone with retry mechanism
            $prayerData = $this->fetchPrayerTimeDataForZone($zoneCode, $dateStart, $dateEnd);

            if ($prayerData === null) {
                $this->newLine();
                $this->warn("✗ Failed to fetch {$zoneCode} after ".self::MAX_RETRIES.' retries');
            } elseif ($prayerData === false) {
                $this->newLine();
            } else {
                // Process and store data
                $processedData = $this->processZoneData($prayerData, $zoneCode, $year);
                $allData = array_merge($allData, $processedData);

                $this->newLine();
                $this->info("✓ Successfully fetched {$zoneCode} ({$zone->negeri} - {$zone->daerah})");
            }

            // Add delay between requests (except for the last one)
            if (!$zone->is($zones->last())) {
                usleep((int) (self::DELAY_SECONDS * 1000000));
            }
        }

        // Write to CSV file
        if (!empty($allData)) {
            $outputPath = $this->writeToCSV($allData, $year);
            $this->info("Data successfully written to: {$outputPath}");
            $this->info('Total records: '.count($allData));

            return Command::SUCCESS;
        } else {
            $this->error('No data was fetched.');

            return Command::FAILURE;
        }
    }

    /**
     * Fetch prayer time data for a specific zone
     */
    private function fetchPrayerTimeDataForZone(string $zoneCode, string $dateStart, string $dateEnd): array|false|null
    {
        $attempt = 0;

        while ($attempt < self::MAX_RETRIES) {
            try {
                $response = Http::asForm()
                    ->timeout(30)
                    ->post(self::API_URL."&zone={$zoneCode}", [
                        'datestart' => $dateStart,
                        'dateend' => $dateEnd,
                    ]);

                if ($response->successful()) {
                    $data = $response->json();

                    if (($data['status'] ?? null) === 'NO_RECORD!') {
                        $this->warn("! No prayer time data available for {$zoneCode}; skipping zone.");

                        return false;
                    }

                    return $data['prayerTime'];
                }

                $attempt++;

                if ($attempt < self::MAX_RETRIES) {
                    sleep(self::RETRY_DELAY);
                }
            } catch (Exception $e) {
                $this->error("Error fetching {$zoneCode}: ".$e->getMessage());
                $attempt++;

                if ($attempt < self::MAX_RETRIES) {
                    sleep(self::RETRY_DELAY);
                }
            }
        }

        return null;
    }

    /**
     * Process zone data into CSV format
     */
    private function processZoneData(array $prayerData, string $zoneCode, int $year): array
    {
        $processedData = [];
        $now = Carbon::now()->toISOString();

        foreach ($prayerData as $prayer) {
            // Parse the date (format: "01-Jan-2026")
            // By default, when fetching from API without a cookie, it will send the time data in Malay locale.
            // Changing the date locale to english can be only done in Webite. It will set cookie _lang=en_uk so the response
            // will be in english locale.
            // Example difference: en (Mar), ms (Mac) etc.
            $date = Carbon::createFromLocaleFormat('d-M-Y', 'ms', $prayer['date'], timezone: 'Asia/Kuala_Lumpur');
            $month = $date->format('m');

            // Convert times to Unix timestamps
            $imsak = $this->timeToTimestamp($date, $prayer['imsak']);
            $fajar = $this->timeToTimestamp($date, $prayer['fajr']);
            $syuruk = $this->timeToTimestamp($date, $prayer['syuruk']);
            $dhuha = $this->timeToTimestamp($date, $prayer['dhuha']);
            $zohor = $this->timeToTimestamp($date, $prayer['dhuhr']);
            $asar = $this->timeToTimestamp($date, $prayer['asr']);
            $maghrib = $this->timeToTimestamp($date, $prayer['maghrib']);
            $isyak = $this->timeToTimestamp($date, $prayer['isha']);

            $processedData[] = [
                'zone' => $zoneCode,
                'year' => $year,
                'month' => $month,
                'tarikh_hijri' => $prayer['hijri'],
                'imsak' => $imsak,
                'fajar' => $fajar,
                'syuruk' => $syuruk,
                'dhuha' => $dhuha,
                'zohor' => $zohor,
                'asar' => $asar,
                'maghrib' => $maghrib,
                'isyak' => $isyak,
                'updated_date' => $now,
                'created_date' => $now,
            ];
        }

        return $processedData;
    }

    /**
     * Convert time string to Unix timestamp
     */
    private function timeToTimestamp(Carbon $date, string $time): int
    {
        $timeParts = explode(':', $time);
        $hour = (int) $timeParts[0];
        $minute = (int) $timeParts[1];
        $second = isset($timeParts[2]) ? (int) $timeParts[2] : 0;

        return $date->copy()
            ->setTime($hour, $minute, $second)
            ->timestamp;
    }

    /**
     * Write data to CSV file
     */
    private function writeToCSV(array $data, int $year): string
    {
        $outputPath = resource_path("csv/Dump-output-{$year}.csv");

        // Create CSV writer
        $csv = Writer::from($outputPath, 'w');

        // Write header
        $headers = ['zone', 'year', 'month', 'tarikh_hijri', 'imsak', 'fajar', 'syuruk', 'dhuha', 'zohor', 'asar', 'maghrib', 'isyak', 'updated_date', 'created_date'];
        $csv->insertOne($headers);

        // Write data rows
        $csv->insertAll($data);

        return $outputPath;
    }
}
