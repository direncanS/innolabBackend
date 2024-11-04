<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AirQuality\Station;
use App\Models\AirQuality\Component;
use App\Models\AirQuality\Measurement;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class FetchAirQualityData extends Command
{
    protected $signature = 'airquality:fetch';
    protected $description = 'Fetch the latest air quality data and store it in the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $response = Http::get('https://www2.land-oberoesterreich.gv.at/imm/jaxrs/messwerte/json');

        if ($response->successful()) {
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

            $this->info('Air quality data has been fetched and stored successfully.');
        } else {
            $this->error('Failed to fetch data from the air quality API.');
        }

        return 0;
    }
}
