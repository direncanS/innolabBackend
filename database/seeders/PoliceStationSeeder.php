<?php

namespace Database\Seeders;

use App\Models\PoliceStation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PoliceStationSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = Storage::disk('public')->path('csv/police_stations.csv');

        if (!file_exists($filePath)) {
            $output = Artisan::call('police:download');
            $this->command->info($output);
        }

        $csvData = fopen($filePath, 'r');
        fgetcsv($csvData, 1000, ',');

        while (($data = fgetcsv($csvData, 1000, ',')) !== FALSE) {
            try {

                $point = $data[1]; // POINT (16.311663 48.212019)
                preg_match('/POINT\s*\(\s*([-\d\.]+)\s+([-\d\.]+)\s*\)/', $point, $matches);

                $longitude = $matches[1] ?? null;
                $latitude = $matches[2] ?? null;

                PoliceStation::create([
                    'fid' => $data[0] ?? null,
                    'name' => $data[2] ?? null,
                    'address' => $data[3] ?? null,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'se_sdo_rowid' => $data[4] ?? null,
                    'se_anno_cad_data' => $data[5] ?? null,
                ]);
            } catch (\Exception $e) {
                Log::error("Error inserting row: " . $e->getMessage());
            }
        }

        fclose($csvData);
        $this->command->info("Police stations successfully transferred.");
    }
}
