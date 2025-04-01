<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use Carbon\Carbon;

class PrayerTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Seeding prayer times from CSV...');
        
        // Path to your CSV file
        // Source of Dump-output.csv: https://github.com/mptwaktusolat/firestore_exporter
        $csvPath = resource_path('csv/Dump-output.csv');
        
        if (!file_exists($csvPath)) {
            $this->command->error('CSV file not found: ' . $csvPath);
            return;
        }
        
        // Create a CSV Reader instance
        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setHeaderOffset(0); // Set the header offset
        
        $records = $csv->getRecords();
        $count = 0;
        
        // Start transaction for better performance
        DB::beginTransaction();
        
        try {
            foreach ($records as $record) {
                // Extract the day from the timestamp (e.g., fajar) and create a date
                $date = Carbon::createFromTimestamp((int)$record['fajar'], 'Asia/Kuala_Lumpur')->toDateString();
                $hijriDate = $record['tarikh_hijri'];
                
                // Convert Unix timestamps to time format
                $fajr = $this->timestampToTimeString($record['fajar']);
                $syuruk = $this->timestampToTimeString($record['syuruk']);
                $dhuhr = $this->timestampToTimeString($record['zohor']);
                $asr = $this->timestampToTimeString($record['asar']);
                $maghrib = $this->timestampToTimeString($record['maghrib']);
                $isha = $this->timestampToTimeString($record['isyak']);
                
                DB::table('prayer_times')->insert([
                    'date' => $date,
                    'location_code' => $record['zone'],
                    'hijri' => $hijriDate,
                    'fajr' => $fajr,
                    'syuruk' => $syuruk,
                    'dhuhr' => $dhuhr,
                    'asr' => $asr,
                    'maghrib' => $maghrib,
                    'isha' => $isha,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $count++;
                
                // Output progress every 1000 records
                if ($count % 1000 === 0) {
                    $this->command->info("Processed $count records...");
                }
            }
            
            DB::commit();
            $this->command->info("Successfully seeded $count prayer time records.");
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error seeding prayer times: ' . $e->getMessage());
            $this->command->error('Line: ' . $e->getLine());
            $this->command->error('File: ' . $e->getFile());
        }
    }
    
    /**
     * Convert Unix timestamp to time string (H:i:s format)
     *
     * @param string|int $timestamp
     * @return string
     */
    private function timestampToTimeString($timestamp)
    {
        if (empty($timestamp)) {
            return null;
        }
        
        return Carbon::createFromTimestamp((int)$timestamp, 'Asia/Kuala_Lumpur')->format('H:i:s');
    }
}
