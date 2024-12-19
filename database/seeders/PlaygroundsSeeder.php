<?php

namespace Database\Seeders;

use App\Models\Playground;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PlaygroundsSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = Storage::disk('public')->path('csv/playgrounds.csv');

        if (!file_exists($filePath)) {
            $output = Artisan::call('playgrounds:download');
            $this->command->info($output);
        }

        $csvData = fopen($filePath, 'r');
        fgetcsv($csvData, 1000, ',');

        while (($data = fgetcsv($csvData, 1000, ',')) !== FALSE) {
            try {

                $point = $data[2]; // Örnek: POINT (16.311663 48.212019)
                preg_match('/POINT \(([-\d\.]+) ([-\d\.]+)\)/', $point, $matches);

                $longitude = $matches[1] ?? null;
                $latitude = $matches[2] ?? null;

                Playground::create([
                    'fid' => $data[0] ?? null,
                    'objectid' => $data[1] ?? null,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'name' => $data[3] ?? null,
                    'district_code' => (int)($data[4] ?? 0),
                    'playground_detail' => $data[5] ?? null,
                    'type_detail' => $data[6] ?? null,
                ]);
            } catch (\Exception $e) {
                Log::error("Error inserting row: " . $e->getMessage());
            }
        }

        fclose($csvData);
        $this->command->info("Playgrounds successfully transferred.");
    }
}
