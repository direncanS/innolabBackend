<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kindergarten;

class KindergartenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csv = fopen(storage_path('csv/kindergartens.csv'), 'r');

        while (($data = fgetcsv($csv, 1000, ',')) !== FALSE) {

            // Longitude ve Latitude değerleri mevcut değilse atla
            if ($this->extractLongitude($data[2]) === null || $this->extractLatitude($data[2]) === null) {
                continue;
            }

            Kindergarten::create([
                'name' => $data[4],  // BEZEICHNUNG (Anaokulu Adı)
                'address' => $data[5],  // ADRESSE (Adres)
                'operator' => $data[4],  // BETREIBER (İşletmeci)
                'type' => $data[7],  // TYP_TXT (Anaokulu Türü)
                'latitude' => $this->extractLatitude($data[2]),  // SHAPE (latitude)
                'longitude' => $this->extractLongitude($data[2]),  // SHAPE (longitude)
            ]);
        }
        fclose($csv);
    }

    private function extractLatitude($shape): ?float
    {
        preg_match('/\(([^)]+)\)/', $shape, $matches);
        if (isset($matches[1])) {
            $coordinates = explode(' ', $matches[1]);
            return isset($coordinates[1]) ? floatval($coordinates[1]) : null;  // Latitude ikinci sırada
        }
        return null;
    }

    private function extractLongitude($shape): ?float
    {
        preg_match('/\(([^)]+)\)/', $shape, $matches);
        if (isset($matches[1])) {
            $coordinates = explode(' ', $matches[1]);
            return isset($coordinates[0]) ? floatval($coordinates[0]) : null;  // Longitude ilk sırada
        }
        return null;
    }
}
