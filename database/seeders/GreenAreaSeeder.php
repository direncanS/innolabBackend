<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GreenArea;

class GreenAreaSeeder extends Seeder
{
    public function run(): void
    {
        $csv = fopen(storage_path('csv/green_areas.csv'), 'r');

        // İlk satırı atla (başlık satırı olabilir)
        fgetcsv($csv);

        while (($data = fgetcsv($csv, 1000, ',')) !== FALSE) {

            // Koordinat ve park alanı adı varsa ekle
            if (!isset($data[2]) || !isset($data[3])) {
                continue;
            }

            $coordinates = $this->extractCoordinates($data[2]);

            GreenArea::create([
                'name' => $data[3],  // Park alanı adı
                'description' => $data[4] ?? null,  // Opsiyonel açıklama
                'latitude' => $coordinates['latitude'],  // SHAPE (latitude)
                'longitude' => $coordinates['longitude'],  // SHAPE (longitude)
            ]);
        }
        fclose($csv);
    }

    private function extractCoordinates($shape): ?array
    {
        // POINT verisindeki enlem ve boylamı çıkart
        preg_match('/\(([^)]+)\)/', $shape, $matches);
        if (isset($matches[1])) {
            $coordinates = explode(' ', $matches[1]);
            return [
                'longitude' => isset($coordinates[0]) ? floatval($coordinates[0]) : null,
                'latitude' => isset($coordinates[1]) ? floatval($coordinates[1]) : null,
            ];
        }
        return null;
    }
}
