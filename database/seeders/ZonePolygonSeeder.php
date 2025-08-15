<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ZonePolygonSeeder extends Seeder
{
    private const BATCH_SIZE = 50;
    private const HTTP_TIMEOUT = 120; // seconds

    /**
     * Run the database seeds. This seeder will populate the zone_polygons table with data from a GeoJSON file.
     */
    public function run(): void
    {
        // Source repository: https://github.com/mptwaktusolat/jakim.geojson
        $url = 'https://raw.githubusercontent.com/mptwaktusolat/jakim.geojson/refs/heads/master/malaysia.district-jakim.geojson';

        $geojsonContent = $this->fetchGeojsonData($url);
        if (!$geojsonContent) {
            return;
        }

        $geojson = json_decode($geojsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error("Invalid JSON data: " . json_last_error_msg());
            return;
        }

        if (!isset($geojson['features']) || !is_array($geojson['features'])) {
            $this->command->error("Invalid GeoJSON structure: missing or invalid 'features' array");
            return;
        }

        $this->processFeatures($geojson['features']);
    }

    /**
     * Fetch GeoJSON data from a URL.
     */
    private function fetchGeojsonData(string $url): ?string
    {
        try {
            $response = Http::timeout(self::HTTP_TIMEOUT)->get($url);

            if ($response->successful()) {
                $content = $response->body();
                $hash = hash('sha256', $content);

                $this->command->info("GeoJSON fetched successfully!");
                $this->command->info("File hash (SHA256): {$hash}");

                return $content;
            } else {
                $this->command->error("Failed to fetch GeoJSON data. HTTP Status: {$response->status()}");
                return null;
            }
        } catch (\Exception $e) {
            $this->command->error("Error fetching GeoJSON data: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Process GeoJSON features.
     */
    private function processFeatures(array $features): void
    {
        $totalFeatures = count($features);
        $this->command->info("Processing {$totalFeatures} features...");

        // Truncate the table before adding new data
        DB::table('zone_polygons')->truncate();

        $batch = [];
        $processed = 0;
        $errors = 0;

        DB::transaction(function () use ($features, &$batch, &$processed, &$errors) {
            foreach ($features as $feature) {
                $record = $this->prepareRecord($feature);

                if ($record) {
                    $batch[] = $record;

                    if (count($batch) >= self::BATCH_SIZE) {
                        $this->insertBatch($batch);
                        $processed += count($batch);
                        $batch = [];

                        if ($processed % 100 === 0) {
                            $this->command->info("Processed {$processed} features...");
                        }
                    }
                } else {
                    $errors++;
                }
            }

            // Insert remaining records
            if (!empty($batch)) {
                $this->insertBatch($batch);
                $processed += count($batch);
            }
        });

        $this->command->info("Completed! Processed: {$processed}, Errors: {$errors}");
    }

    private function prepareRecord(array $feature): ?array
    {
        $properties = $feature['properties'] ?? [];
        $geometry = $feature['geometry'] ?? null;

        // Validate required data
        if (!$geometry || !isset($geometry['type']) || !isset($geometry['coordinates'])) {
            $this->command->warn("Skipping feature with invalid geometry: " . ($feature['id'] ?? 'unknown'));
            return null;
        }

        // Validate geometry JSON
        $geometryJson = json_encode($geometry);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->warn("Skipping feature with invalid geometry JSON: " . ($feature['id'] ?? 'unknown'));
            return null;
        }

        return [
            'string_id'   => $feature['id'] ?? null,
            'name'        => $properties['name'] ?? null,
            'code_state'  => $properties['code_state'] ?? null,
            'state'       => $properties['state'] ?? null,
            'jakim_code'  => $properties['jakim_code'] ?? null,
            'polygon'     => DB::raw("ST_GeomFromGeoJSON('{$geometryJson}')"),
        ];
    }

    private function insertBatch(array $batch): void
    {
        try {
            DB::table('zone_polygons')->insert($batch);
        } catch (\Exception $e) {
            $this->command->error("Error inserting batch: " . $e->getMessage());

            // Fallback: try inserting records one by one to identify problematic records
            foreach ($batch as $record) {
                try {
                    DB::table('zone_polygons')->insert($record);
                } catch (\Exception $individualError) {
                    $this->command->warn("Failed to insert record: " . ($record['string_id'] ?? 'unknown') . " - " . $individualError->getMessage());
                }
            }
        }
    }
}
