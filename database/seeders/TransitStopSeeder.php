<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransitStop;

class TransitStopSeeder extends Seeder
{
    public function run(): void
    {
        $csv = fopen(storage_path('csv/transit_stops.csv'), 'r');

        // İlk satırı atla (başlık satırı olabilir)
        fgetcsv($csv);

        while (($data = fgetcsv($csv, 1000, ',')) !== FALSE) {

            // SHAPE sütunu ve durak adı sütunu (TEXT13ID) var mı kontrol et
            if (!isset($data[2]) || !isset($data[3]) || $this->extractLongitude($data[2]) === null || $this->extractLatitude($data[2]) === null) {
                continue;
            }

            TransitStop::create([
                'name' => $data[3],  // Durak Adı ("TEXT13ID" sütunu)
                'address' => null,   // Adres verisi yok
                'latitude' => $this->extractLatitude($data[2]),  // SHAPE (latitude)
                'longitude' => $this->extractLongitude($data[2]),  // SHAPE (longitude)
            ]);
        }
        fclose($csv);
    }

    private function extractLatitude($shape): ?float
    {
        // SHAPE verisindeki enlem ve boylamı çıkart
        preg_match('/\(([^)]+)\)/', $shape, $matches);
        if (isset($matches[1])) {
            $coordinates = explode(' ', $matches[1]);
            return isset($coordinates[1]) ? floatval($coordinates[1]) : null;  // Latitude ikinci sırada
        }
        return null;
    }

    private function extractLongitude($shape): ?float
    {
        // SHAPE verisindeki enlem ve boylamı çıkart
        preg_match('/\(([^)]+)\)/', $shape, $matches);
        if (isset($matches[1])) {
            $coordinates = explode(' ', $matches[1]);
            return isset($coordinates[0]) ? floatval($coordinates[0]) : null;  // Longitude ilk sırada
        }
        return null;
    }
}
