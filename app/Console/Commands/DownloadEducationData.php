<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class DownloadEducationData extends Command
{
    protected $signature = 'education:download';
    protected $description = 'Download the education CSV file and store it in the storage/csv folder';

    public function handle()
    {
        $url = 'https://www.wien.gv.at/gogv/l9ogdviebezbizeduatt2008f';
        $filePath = 'storage/csv/education.csv';

        $response = Http::get($url);

        if ($response->successful()) {
            Storage::put($filePath, $response->body());
            $this->info('Education CSV file downloaded and saved.');
        } else {
            $this->error('Failed to download the CSV file.');
        }
    }
}
