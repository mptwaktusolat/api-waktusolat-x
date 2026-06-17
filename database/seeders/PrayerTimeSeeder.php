<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class PrayerTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=PrayerTimeSeeder
     *
     * @return void
     */
    public function run()
    {
        // Increase memory limit for this script.
        ini_set('memory_limit', '256M');
        $this->command->info('Seeding prayer times from CSV...');

        // Paths to the prayer times CSV files
        $csvPaths = [
            // Source of Dump-output-2023-2024.csv: https://github.com/mptwaktusolat/firestore_exporter
            resource_path('csv/Dump-output-2023-2024.csv'),
            // Add new CSV files for each year below. Read more on docs/update-data-from-esolat/README.md
            resource_path('csv/Dump-output-2025.csv'),
            resource_path('csv/Dump-output-2026.csv'),
        ];

        $totalProcessed = 0;

        foreach ($csvPaths as $csvPath) {
            if (!file_exists($csvPath)) {
                $this->command->warn("CSV file not found, skipping: $csvPath");

                continue;
            }

            $this->command->info("Processing file: $csvPath");
            $count = $this->processCsvFile($csvPath);
            $totalProcessed += $count;
        }

        $this->command->info("Total prayer time records seeded: $totalProcessed");
    }

    /**
     * Process a single CSV file
     *
     * @return int Number of records processed
     */
    private function processCsvFile(string $csvPath)
    {
        // Create a CSV Reader instance
        $csv = Reader::from($csvPath, 'r');
        $csv->setHeaderOffset(0); // Set the header offset

        $records = $csv->getRecords();
        $count = 0;
        $batchSize = 1000; // Process in batches of 1000 records
        $batch = [];
        $now = now()->format('Y-m-d H:i:s'); // Cache the timestamp

        DB::beginTransaction();

        try {
            foreach ($records as $record) {
                // Extract the day from the timestamp (e.g., fajar) and create a date
                $date = Carbon::createFromTimestamp((int) $record['fajar'], 'Asia/Kuala_Lumpur')->toDateString();
                $hijriDate = $record['tarikh_hijri'];
                $locationCode = $record['zone'];

                $imsakTimestamp = !empty($record['imsak']) ? (int) $record['imsak'] : ((int) $record['fajar'] - 600); // Imsak is 10 minutes before Fajr
                $dhuhaTimestamp = !empty($record['dhuha']) ? (int) $record['dhuha'] : null;

                // Convert Unix timestamps to time format
                $imsak = $this->timestampToTimeString($imsakTimestamp);
                $fajr = $this->timestampToTimeString($record['fajar']);
                $syuruk = $this->timestampToTimeString($record['syuruk']);
                $dhuha = $dhuhaTimestamp !== null ? $this->timestampToTimeString($dhuhaTimestamp) : null;
                $dhuhr = $this->timestampToTimeString($record['zohor']);
                $asr = $this->timestampToTimeString($record['asar']);
                $maghrib = $this->timestampToTimeString($record['maghrib']);
                $isha = $this->timestampToTimeString($record['isyak']);

                $batch[] = [
                    'date' => $date,
                    'location_code' => $locationCode,
                    'hijri' => $hijriDate,
                    'imsak' => $imsak,
                    'fajr' => $fajr,
                    'syuruk' => $syuruk,
                    'dhuha' => $dhuha,
                    'dhuhr' => $dhuhr,
                    'asr' => $asr,
                    'maghrib' => $maghrib,
                    'isha' => $isha,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                $count++;

                // Insert batch when it reaches the batch size
                if (count($batch) >= $batchSize) {
                    DB::table('prayer_times')->insert($batch);
                    $batch = []; // Reset batch
                    $this->command->info("  Processed $count records...");
                }
            }

            // Insert any remaining records in the batch
            if (!empty($batch)) {
                DB::table('prayer_times')->insert($batch);
            }

            DB::commit();
            $this->command->info("  Successfully seeded $count new records from this file.");

        } catch (\Exception $e) {
            DB::rollBack();

            $this->command->error('Error seeding prayer times.');

            $errorCode = $e->getCode();
            if ($errorCode == 23000) {
                $this->command->warn("Error code {$errorCode} indicates duplicate key violation. Existing records may already be present.");
            } else {
                $this->command->error("Error code: {$errorCode}");
                $this->command->error("Message: {$e->getMessage()}");
            }
        }
    }

    /**
     * Convert Unix timestamp to time string (H:i:s format)
     *
     * @return string|null The Time string. Example "05:30:00"
     */
    private function timestampToTimeString(int $timestamp): ?string
    {
        if (empty($timestamp)) {
            return null;
        }

        $time = Carbon::createFromTimestamp($timestamp, 'Asia/Kuala_Lumpur')->format('H:i:s');

        // Skip midnight times (00:00:00) as they may be invalid/default values from JAKIM API
        if ($time === '00:00:00') {
            return null;
        }

        return $time;
    }
}
