<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Polygon;
use proj4php\Proj4php;
use proj4php\Proj;
use proj4php\Point;

class PolygonSeeder extends Seeder
{
    public function run(): void
    {
        // Initialize Proj4php
        $proj4 = new Proj4php();

        // Manually define EPSG:31256 projection parameters
        $proj4->addDef("EPSG:31256", "+proj=tmerc +lat_0=0 +lon_0=16.33333333333333 +k=1 +x_0=0 +y_0=0 +datum=hermannskogel +units=m +no_defs");

        // Define projections: EPSG:31256 (source) and WGS84 (target)
        $sourceProj = new Proj("EPSG:31256", $proj4);
        $targetProj = new Proj("EPSG:4326", $proj4);

        $csv = fopen(storage_path('csv/polygons.csv'), 'r');

        // Skip the header row
        fgetcsv($csv, 1000, ',');

        while (($data = fgetcsv($csv, 1000, ',')) !== FALSE) {
            $coordinates = $this->extractPolygonCoordinates($data[1]);
            $convertedCoordinates = [];

            foreach ($coordinates as $coord) {
                // Transform each coordinate from EPSG:31256 to EPSG:4326
                $pointSrc = new Point($coord['longitude'], $coord['latitude'], $sourceProj);
                $pointDst = $proj4->transform($targetProj, $pointSrc);

                $convertedCoordinates[] = [
                    'longitude' => round($pointDst->x, 6),
                    'latitude' => round($pointDst->y, 6),
                ];
            }

            // Save the transformed polygon in the database
            Polygon::create([
                'district_code' => $data[12],
                'name' => $data[2],
                'shape' => json_encode($convertedCoordinates),
            ]);
        }
        fclose($csv);
    }

    private function extractPolygonCoordinates($shape): ?array
    {
        preg_match('/\(\(([^)]+)\)\)/', $shape, $matches);
        if (isset($matches[1])) {
            $points = explode(',', $matches[1]);
            $coordinates = [];

            foreach ($points as $point) {
                $coord = explode(' ', trim($point));
                if (count($coord) === 2) {
                    $coordinates[] = [
                        'longitude' => (float) $coord[0],
                        'latitude' => (float) $coord[1],
                    ];
                }
            }
            return $coordinates;
        }
        return null;
    }
}
