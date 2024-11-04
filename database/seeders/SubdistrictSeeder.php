<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subdistrict;
use App\Models\District;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SubdistrictSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = storage_path('csv/districts.csv');

        if (!file_exists($filePath)) {
            $this->command->error("CSV file not found.");
            return;
        }

        $csvData = fopen($filePath, 'r');
        $headers = fgetcsv($csvData, 1000, ';');

        while (($data = fgetcsv($csvData, 1000, ';')) !== FALSE) {
            $districtCode = $data[3];
            $subdistrictCode = $data[5];
            $subdistrictName = $data[6];

            $district = District::where('code', $districtCode)->first();

            if ($district) {
                Subdistrict::updateOrCreate(
                    ['code' => $subdistrictCode],
                    [
                        'name' => $subdistrictName,
                        'district_id' => $district->id,
                    ]
                );
            } else {
                Log::warning("District not found for code: $districtCode");
            }
        }

        fclose($csvData);
        $this->command->info('Subdistrict data has been seeded successfully.');
    }
}
