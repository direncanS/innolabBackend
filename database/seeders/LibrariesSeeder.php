<?php

namespace Database\Seeders;

use App\Models\Library;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LibrariesSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = Storage::disk('public')->path('csv/libraries.csv');

        if (!file_exists($filePath)) {
            $output = Artisan::call('libraries:download');
            $this->command->info($output);
        }

        $csvData = fopen($filePath, 'r');
        fgetcsv($csvData, 1000, ',');

        while (($data = fgetcsv($csvData, 1000, ',')) !== FALSE) {
            try {

                $point = $data[1]; // POINT (16.343298049538593 48.187639718475815)
                preg_match('/POINT\s*\(\s*([-\d\.]+)\s+([-\d\.]+)\s*\)/', $point, $matches);

                $longitude = $matches[1] ?? null;
                $latitude = $matches[2] ?? null;

                Library::create([
                    'fid' => $data[0] ?? null,
                    'name' => $data[2] ?? null,
                    'address' => $data[3] ?? null,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'telephone' => $data[10] ?? null,
                    'email' => $data[11] ?? null,
                    'opening_times_1' => $data[4] ?? null,
                    'opening_times_2' => $data[5] ?? null,
                    'opening_times_3' => $data[6] ?? null,
                    'opening_times_4' => $data[7] ?? null,
                    'opening_times_5' => $data[8] ?? null,
                ]);
            } catch (\Exception $e) {
                Log::error("Error inserting row: " . $e->getMessage());
            }
        }

        fclose($csvData);
        $this->command->info("Libraries successfully transferred.");
    }
}
