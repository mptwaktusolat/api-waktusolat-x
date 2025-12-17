<?php

namespace App\Console\Commands\Db;

use Illuminate\Console\Command;

/**
 * Script to update the 2026 data to match timezone offset issue. This script is only use once,
 * I leave it here for reference purpose. See issue https://github.com/mptwaktusolat/api-waktusolat-x/issues/28 for details.
 */
class FixTimestampTimezoneOffset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-timestamp-timezone-offset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read prayer data dump CSV file that contains malformed timestamps and fix the timezone offset issue.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Read from resources/csv/Dump-output-2026.csv
        $inputFilePath = resource_path('csv/Dump-output-2026.csv');

        if (!file_exists($inputFilePath)) {
            $this->error("Input file not found: $inputFilePath");
            return 1;
        }

        $csv = \League\Csv\Reader::from($inputFilePath, 'r');
        $csv->setHeaderOffset(0); // first row is header

        $outputFilePath = resource_path('csv/Dump-output-2026-fixed.csv');
        $writer = \League\Csv\Writer::from($outputFilePath, 'w');

        $headers = $csv->getHeader();
        if (!is_array($headers) || empty($headers)) {
            $this->error('CSV header row is missing or invalid.');
            return 1;
        }
        $writer->insertOne($headers);

        $count = 0;

        // Adjust prayer timestamps by subtracting 8 hours
        foreach ($csv->getRecords() as $row) {
            $prayerFields = ['fajar', 'syuruk', 'zohor', 'asar', 'maghrib', 'isyak'];
            foreach ($prayerFields as $field) {
                if (isset($row[$field]) && is_numeric($row[$field])) {
                    $row[$field] = (string)((int)$row[$field] - (8 * 60 * 60));
                }
            }
            $ordered = [];
            foreach ($headers as $h) {
                $ordered[] = $row[$h] ?? '';
            }
            $writer->insertOne($ordered);
            $count++;
        }

        $this->info("Wrote $count adjusted rows to: $outputFilePath");
    }
}
