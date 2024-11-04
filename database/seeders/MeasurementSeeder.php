<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AirQuality\Station;
use App\Models\AirQuality\Component;
use App\Models\AirQuality\Measurement;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class MeasurementSeeder extends Seeder
{
    public function run()
    {
        $response = Http::get('https://www2.land-oberoesterreich.gv.at/imm/jaxrs/messwerte/json');
        $measurements = $response->json()['messwerte'];

        foreach ($measurements as $data) {
            $station = Station::where('code', $data['station'])->first();
            $component = Component::where('code', $data['komponente'])->first();

            if ($station && $component) {
                Measurement::updateOrCreate(
                    [
                        'station_id' => $station->id,
                        'component_id' => $component->id,
                        'measurement_time' => Carbon::createFromTimestampMs($data['zeitpunkt']),
                    ],
                    [
                        'value' => str_replace(',', '.', $data['messwert']),
                        'unit' => $data['einheit']
                    ]
                );
            }
        }
    }
}
