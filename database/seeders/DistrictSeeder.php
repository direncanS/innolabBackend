<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\District;

class DistrictSeeder extends Seeder
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

        $districtCodes = [];

        while (($data = fgetcsv($csvData, 1000, ';')) !== FALSE) {
            $districtCode = $data[3];
            $districtName = $data[6];

            if (!in_array($districtCode, $districtCodes)) {
                District::updateOrCreate(
                    ['code' => $districtCode],
                    ['name' => $districtName]
                );
                $districtCodes[] = $districtCode;
            }
        }

        fclose($csvData);
        $this->command->info('District data has been seeded successfully.');
    }
}
