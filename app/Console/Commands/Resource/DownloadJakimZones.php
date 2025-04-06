<?php

namespace App\Console\Commands\Resource;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DownloadJakimZones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resource:download-jakim-zones';

    /**
    * A brief description of the console command.
     *
     * @var string
     */
    protected $description = 'Download JAKIM Zones from repo and store in resource folder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = 'https://raw.githubusercontent.com/mptwaktusolat/jakim-zones-grabber/main/locations.json';
        $destinationPath = resource_path('json/locations.json');

        try {
            $this->info('Downloading locations.json...');
            $jsonData = Http::get($url)->body();

            if (empty($jsonData)) {
                $this->error('Failed to download locations.json.');
                return 1;
            }

            if (!file_exists(dirname($destinationPath))) {
                mkdir(dirname($destinationPath), 0755, true);
            }

            file_put_contents($destinationPath, $jsonData);
            $this->info('locations.json has been successfully downloaded and stored in ' . $destinationPath);

            $this->info('Reminder: Please run the appropriate database seeder to seed the data.');

            return 0;
        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
            return 1;
        }
    }
}
