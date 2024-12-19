<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class DownloadSocialMarketsData extends Command
{
    protected $signature = 'socialmarkets:download';
    protected $description = 'Download the social markets CSV file and store it in the storage/csv folder';

    public function handle()
    {
        $url = 'https://data.wien.gv.at/daten/geo?service=WFS&request=GetFeature&version=1.1.0&typeName=ogdwien:SOZIALMARKTOGD&srsName=EPSG:4326&outputFormat=csv';
        $filePath = 'csv/socialmarkets.csv';

        $response = Http::get($url);

        if ($response->successful()) {
            Storage::disk('public')->put($filePath, $response->body());
            $this->info('Social Markets CSV file downloaded and saved.');
        } else {
            $this->error('Failed to download the CSV file.');
        }
    }
}
