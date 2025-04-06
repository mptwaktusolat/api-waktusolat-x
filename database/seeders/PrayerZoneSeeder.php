<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PrayerZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding prayer zones from JSON file...');
        
        // Path to the JSON file
        $jsonPath = resource_path('json/locations.json');
        
        if (!file_exists($jsonPath)) {
            $this->command->error('JSON file not found: ' . $jsonPath);
            return;
        }
        
        $jsonData = File::get($jsonPath);
        $zones = json_decode($jsonData, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('Error decoding JSON: ' . json_last_error_msg());
            return;
        }
        
        $count = 0;
        
        DB::beginTransaction();
        
        try {
            foreach ($zones as $zone) {
                DB::table('prayer_zones')->insert([
                    'jakim_code' => $zone['jakimCode'],
                    'negeri' => $zone['negeri'],
                    'daerah' => $zone['daerah'],
                ]);
                
                $count++;
            }
            
            DB::commit();
            $this->command->info("Successfully seeded $count prayer zone records.");
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error seeding prayer zones: ' . $e->getMessage());
            $this->command->error('Line: ' . $e->getLine());
            $this->command->error('File: ' . $e->getFile());
        }
    }
}
