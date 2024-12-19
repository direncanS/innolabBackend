<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EducationData;
use App\Models\District;
use App\Models\Subdistrict;
use Carbon\Carbon;
<<<<<<< HEAD
use Illuminate\Support\Facades\Log;
=======
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
>>>>>>> 8dbac16 (last version)

class EducationSeeder extends Seeder
{
    public function run(): void
    {
<<<<<<< HEAD
        $filePath = storage_path('csv/education.csv');

        if (!file_exists($filePath)) {
            $this->command->error("CSV file not found. Run 'php artisan education:download' first.");
            return;
=======
        $filePath = Storage::disk('public')->path('csv/education.csv');

        if (!file_exists($filePath)) {
            $output = Artisan::call('education:download');
            $this->command->info($output);
>>>>>>> 8dbac16 (last version)
        }

        $csvData = fopen($filePath, 'r');

        $headers = fgetcsv($csvData, 1000, ';');
        $processedCount = 0;
        $skippedCount = 0;

        while (($data = fgetcsv($csvData, 1000, ';')) !== FALSE) {
            if ($data[0] === 'NUTS') {
                continue;
            }

            if ($data[1] == '90000') {
                $skippedCount++;
                continue;
            }

            if (count($data) < 14) {
                $skippedCount++;
                continue;
            }

            try {
                $refDate = Carbon::createFromFormat('Ymd', $data[4])->format('Y-m-d');
            } catch (\Exception $e) {
                $skippedCount++;
                continue;
            }

            $district = District::where('code', $data[1])->first();
            $subdistrict = District::where('code', $data[1])->first();

            if (!$subdistrict) {
                $skippedCount++;
                continue;
            }

            try {
                EducationData::updateOrCreate(
                    [
                        'district_id' => $district->id,
                        'sub_district_id' => $subdistrict->id,
                        'ref_year' => intval($data[3]),
                        'ref_date' => $refDate,
                    ],
                    [
                        'nuts' => $data[0],
                        'edu_all' => floatval(str_replace(',', '.', $data[5] ?? null)),
                        'edu_leh' => floatval(str_replace(',', '.', $data[6] ?? null)),
                        'edu_bms' => floatval(str_replace(',', '.', $data[7] ?? null)),
                        'edu_ahs' => floatval(str_replace(',', '.', $data[8] ?? null)),
                        'edu_bhs' => floatval(str_replace(',', '.', $data[9] ?? null)),
                        'edu_kol' => floatval(str_replace(',', '.', $data[10] ?? null)),
                        'edu_aca' => floatval(str_replace(',', '.', $data[11] ?? null)),
                        'edu_uni' => floatval(str_replace(',', '.', $data[12] ?? null)),
                        'edu_aka' => floatval(str_replace(',', '.', $data[13] ?? null)),
                    ]
                );
                $processedCount++;
            } catch (\Exception $e) {
                $skippedCount++;
            }
        }

        fclose($csvData);

    }
}
