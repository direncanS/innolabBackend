<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AirQuality\Component;
use App\Models\AirQuality\Measurement;
use App\Models\AirQuality\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AirQualityController extends Controller
{
    public function getAirQualityByLocation(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        // Belirli bir yarıçap içinde en yakın istasyonu bul
        $radius = 10; // 10 km yarıçap
        $station = Station::selectRaw("*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance", [$latitude, $longitude, $latitude])
            ->having('distance', '<', $radius)
            ->orderBy('distance')
            ->first();

        if ($station) {
            // İlgili istasyondaki hava kalitesi ölçümlerini getir
            $measurements = Measurement::where('station_id', $station->id)
                ->latest('measurement_time')
                ->get();

            $airQualityData = $measurements->map(function ($measurement) {
                return [
                    'component' => $measurement->component->name,
                    'value' => $measurement->value,
                    'unit' => $measurement->unit,
                    'time' => $measurement->measurement_time,
                ];
            });

            return response()->json([
                'station' => [
                    'name' => $station->short_name,
                    'latitude' => $station->latitude,
                    'longitude' => $station->longitude,
                ],
                'air_quality_data' => $airQualityData
            ]);
        } else {
            // En yakın istasyon bulunamazsa varsayılan bir cevap döndür
            return response()->json([
                'message' => 'No nearby air quality station found',
                'default_air_quality_score' => 50 // Varsayılan bir hava kalitesi puanı
            ]);
        }
    }
}
