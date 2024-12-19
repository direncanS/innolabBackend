<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AirQuality\Station;
use Illuminate\Support\Facades\Http;

class StationSeeder extends Seeder
{
    public function run()
    {
        $response = Http::get('https://www2.land-oberoesterreich.gv.at/imm/jaxrs/stationen/json');
        $stations = $response->json()['stationen'];

        foreach ($stations as $station) {
            Station::updateOrCreate(
<<<<<<< HEAD
                ['code' => $station['code']], // Bu kod zaten varsa güncelle
=======
                ['code' => $station['code']],
>>>>>>> 8dbac16 (last version)
                [
                    'short_name' => $station['kurzname'],
                    'long_name' => $station['langname'],
                    'latitude' => $station['geoBreite'],
                    'longitude' => $station['geoLaenge'],
                    'component_codes' => $station['komponentenCodes']
                ]
            );
        }
    }
}
