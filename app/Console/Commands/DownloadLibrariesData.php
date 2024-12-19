<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class DownloadLibrariesData extends Command
{
    protected $signature = 'libraries:download';
    protected $description = 'Download the libraries CSV file and store it in the storage/csv folder';

    public function handle()
    {
        $url = 'https://data.wien.gv.at/daten/geo?service=WFS&request=GetFeature&version=1.1.0&typeName=ogdwien:BUECHEREIOGD&srsName=EPSG:4326&outputFormat=csv';
        $filePath = 'csv/libraries.csv';

        $response = Http::get($url);

        if ($response->successful()) {
            Storage::disk('public')->put($filePath, $response->body());
            $this->info('Libraries CSV file downloaded and saved.');
        } else {
            $this->error('Failed to download the CSV file.');
        }
    }
}
