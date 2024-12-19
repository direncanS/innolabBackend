<?php

namespace Database\Seeders;

use App\Models\SocialMarket;
use Illuminate\Database\Seeder;
use App\Models\EducationData;
use App\Models\District;
use App\Models\Subdistrict;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SocialMarketsSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = Storage::disk('public')->path('csv/socialmarkets.csv');

        if (!file_exists($filePath)) {
            $output = Artisan::call('socialmarkets:download');
            $this->command->info($output);
        }

        $csvData = fopen($filePath, 'r');
        fgetcsv($csvData, 1000, ','); // Skip the header row

        while (($data = fgetcsv($csvData, 1000, ',')) !== FALSE) {
            try {

                $point = $data[1]; // Örnek: POINT (16.311663 48.212019)
                preg_match('/POINT \(([-\d\.]+) ([-\d\.]+)\)/', $point, $matches);

                $longitude = $matches[1] ?? null;
                $latitude = $matches[2] ?? null;

                SocialMarket::create([
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'name' => $data[2] ?? null,
                    'address' => $data[3] ?? null,
                    'district_code' => (int)($data[4] ?? 0),
                    'langtext' => $data[5] ?? null,
                    'web_link' => $data[7] ?? null,
                ]);
            } catch (\Exception $e) {
                Log::error("Error inserting row: " . $e->getMessage() . " Data: " . json_encode($data));
            }
        }

        fclose($csvData);

    }
}
