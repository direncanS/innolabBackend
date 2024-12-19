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
<<<<<<< HEAD
        $filePath = 'storage/csv/education.csv';
=======
        $filePath = 'csv/education.csv';
>>>>>>> 8dbac16 (last version)

        $response = Http::get($url);

        if ($response->successful()) {
<<<<<<< HEAD
            Storage::put($filePath, $response->body());
=======
            Storage::disk('public')->put($filePath, $response->body());
>>>>>>> 8dbac16 (last version)
            $this->info('Education CSV file downloaded and saved.');
        } else {
            $this->error('Failed to download the CSV file.');
        }
    }
}
