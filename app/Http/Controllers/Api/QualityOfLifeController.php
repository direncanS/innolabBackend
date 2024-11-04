<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AirQuality\Measurement;
use App\Models\AirQuality\Station;
use App\Models\District;
use App\Models\EducationData;
use App\Models\GreenArea;
use App\Models\Kindergarten;
use App\Models\Subdistrict;
use App\Models\TransitStop;
use Illuminate\Http\Request;

class QualityOfLifeController extends Controller
{
    public function calculate(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $userType = $request->input('user-type');
        $subdistrictName = $request->input('subdistrict_name');

        $subdistrict = $this->findSubdistrictByName($subdistrictName);

        if (!$subdistrict) {
            return response()->json(['message' => 'No subdistrict found for the provided data'], 404);
        }

        $educationLevel = $this->getEducationLevelBySubdistrict($subdistrict->id);
        $kindergartens = $this->getNearbyKindergartens($latitude, $longitude);
        $parks = $this->getNearbyParks($latitude, $longitude);
        $transitStops = $this->getNearbyTransitStops($latitude, $longitude);
        $airQuality = $this->getAirQuality($latitude, $longitude);

        $qualityOfLifeScore = $this->calculateQualityOfLifeScore($userType, $transitStops, $parks, $kindergartens, $airQuality, $educationLevel);

        return response()->json([
            'quality_of_life_score' => $qualityOfLifeScore,
            'normalized_score' => $this->normalizeScore($qualityOfLifeScore, $userType),
            'recommendation' => $this->getRecommendation($qualityOfLifeScore, $userType),
            'subdistrict' => $subdistrict,
            'education_level' => $educationLevel,
            'air_quality' => $airQuality,
            'number_of_kindergartens' => count($kindergartens),
            'number_of_parks' => count($parks),
            'number_of_transit_stops' => count($transitStops),
            'kindergartens' => $kindergartens,
            'parks' => $parks,
            'transit_stops' => $transitStops,
        ]);
    }

    private function calculateQualityOfLifeScore($userType, $transitStops, $parks, $kindergartens, $airQuality, $educationLevel)
    {
        // Calculate per-component scores between 0 and 100
        $transitScore = $this->calculateTransitScore($transitStops);
        $kindergartenScore = $this->calculateKindergartenScore($kindergartens);
        $parkScore = $this->calculateParkScore($parks);
        $airQualityScore = $airQuality; // Assuming airQuality is between 0 and 100
        $educationLevelScore = $this->calculateEducationLevelScore($educationLevel);

        // User-specific weight adjustments for balanced scoring
        $weights = [
            'student' => [
                'transit' => 0.3,
                'education' => 0.25,
                'parks' => 0.2,
                'airQuality' => 0.15,
                'kindergarten' => 0.1
            ],
            'parent' => [
                'kindergarten' => 0.3,
                'parks' => 0.25,
                'airQuality' => 0.2,
                'transit' => 0.15,
                'education' => 0.1
            ],
            'retiree' => [
                'parks' => 0.4,
                'airQuality' => 0.3,
                'transit' => 0.15,
                'kindergarten' => 0.075,
                'education' => 0.075
            ],
            'default' => [
                'kindergarten' => 0.2,
                'transit' => 0.3,
                'education' => 0.2,
                'parks' => 0.2,
                'airQuality' => 0.1
            ]
        ];

        $userWeights = $weights[$userType] ?? $weights['default'];

        // Calculate total score as weighted average
        $score = ($transitScore * $userWeights['transit']) +
            ($kindergartenScore * $userWeights['kindergarten']) +
            ($parkScore * $userWeights['parks']) +
            ($airQualityScore * $userWeights['airQuality']) +
            ($educationLevelScore * $userWeights['education']);

        return $score;
    }

    private function normalizeScore($rawScore)
    {
        return min(100, max(0, $rawScore));
    }

    private function getRecommendation($score, $userType)
    {
        $userType = $userType ?: 'Allgemeiner Benutzer';

        if ($score >= 90) {
            return "$userType ist sehr geeignet.";
        } elseif ($score >= 80) {
            return "$userType ist ziemlich geeignet.";
        } elseif ($score >= 70) {
            return "$userType ist geeignet.";
        } elseif ($score >= 60) {
            return "$userType ist durchschnittlich geeignet.";
        } elseif ($score >= 50) {
            return "$userType ist teilweise geeignet.";
        } elseif ($score >= 40) {
            return "$userType ist wenig geeignet.";
        } elseif ($score >= 30) {
            return "$userType ist sehr wenig geeignet.";
        } elseif ($score >= 20) {
            return "$userType ist nicht geeignet.";
        } else {
            return "$userType ist überhaupt nicht geeignet.";
        }
    }

    private function calculateTransitScore($transitStops)
    {
        if (count($transitStops) == 0) {
            return 0;
        }

        $nearestDistance = $transitStops->min('distance');

        if ($nearestDistance <= 0.5) {
            return 100;
        } elseif ($nearestDistance <= 1) {
            return 80;
        } elseif ($nearestDistance <= 2) {
            return 60;
        } elseif ($nearestDistance <= 5) {
            return 40;
        } else {
            return 0;
        }
    }

    private function calculateParkScore($parks)
    {
        if (count($parks) == 0) {
            return 0;
        }

        $nearestDistance = $parks->min('distance');

        if ($nearestDistance <= 0.5) {
            return 100;
        } elseif ($nearestDistance <= 1) {
            return 80;
        } elseif ($nearestDistance <= 2) {
            return 60;
        } elseif ($nearestDistance <= 5) {
            return 40;
        } else {
            return 0;
        }
    }

    private function calculateKindergartenScore($kindergartens)
    {
        if (count($kindergartens) == 0) {
            return 0;
        }

        $nearestDistance = $kindergartens->min('distance');

        if ($nearestDistance <= 0.5) {
            return 100;
        } elseif ($nearestDistance <= 1) {
            return 80;
        } elseif ($nearestDistance <= 2) {
            return 60;
        } elseif ($nearestDistance <= 5) {
            return 40;
        } else {
            return 0;
        }
    }

    private function calculateEducationLevelScore($educationLevel)
    {
        // Normalize education level to a score between 0 and 100
        return min(max($educationLevel, 0), 100);
    }

    private function getAirQuality($latitude, $longitude, $radius = 10)
    {
        $station = Station::selectRaw("*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) )
        * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance",
            [$latitude, $longitude, $latitude])
            ->having('distance', '<', $radius)
            ->orderBy('distance')
            ->first();

        if (!$station) {
            return 50; // Default score if no station is found within radius
        }

        // Fetch latest measurements for each component at the station
        $measurements = Measurement::where('station_id', $station->id)
            ->whereIn('component_id', [1, 2, 21, 17, 13])
            ->latest('measurement_time')
            ->get();

        // Calculate individual component scores
        $componentScores = [];
        foreach ($measurements as $measurement) {
            $componentScores[] = $this->calculateComponentScore($measurement);
        }

        // Calculate average score
        if (count($componentScores) > 0) {
            $airQualityScore = array_sum($componentScores) / count($componentScores);
        } else {
            $airQualityScore = 50; // Default score if no data is available
        }

        return $airQualityScore;
    }

    private function calculateComponentScore($measurement)
    {
        $value = $measurement->value;
        $componentCode = $measurement->component->code;

        switch ($componentCode) {
            case 'PM10kont': // Particulate Matter
                return $this->normalizeValue($value, [0, 20, 50, 100, 150]);

            case 'PM25kont': // Particulate Matter
                return $this->normalizeValue($value, [0, 10, 25, 50, 75]);

            case 'NO2': // Nitrogen Dioxide
                return $this->normalizeValue($value, [0, 40, 100, 200, 400]);

            case 'SO2': // Sulfur Dioxide
                return $this->normalizeValue($value, [0, 100, 200, 350, 500]);

            case 'O3': // Ozone
                return $this->normalizeValue($value, [0, 80, 120, 180, 240]);

            default:
                return 0; // Unknown or unhandled component
        }
    }

    private function normalizeValue($value, $thresholds)
    {
        // Normalize value to a score between 0 and 100 based on the thresholds
        if ($value <= $thresholds[0]) return 100;
        if ($value <= $thresholds[1]) return 75;
        if ($value <= $thresholds[2]) return 50;
        if ($value <= $thresholds[3]) return 25;
        return 0;
    }

    private function findSubdistrictByName($name)
    {
        return Subdistrict::where('name', 'LIKE', '%' . $name . '%')->first();
    }

    private function getEducationLevelBySubdistrict($subdistrictId)
    {
        $districtId = Subdistrict::find($subdistrictId)->district_id;
        $educationData = EducationData::where('sub_district_id', $districtId)->first();
        return $educationData->edu_all ?? 0;
    }

    private function getNearbyKindergartens($latitude, $longitude)
    {
        return Kindergarten::selectRaw("*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance", [$latitude, $longitude, $latitude])
            ->having('distance', '<', 1)
            ->orderBy('distance')
            ->get();
    }

    private function getNearbyParks($latitude, $longitude, $radius = 1)
    {
        return GreenArea::selectRaw("
        id, name, description, latitude, longitude,
        ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) )
        * cos( radians( longitude ) - radians(?) )
        + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance
    ", [$latitude, $longitude, $latitude])
            ->having('distance', '<', $radius)
            ->orderBy('distance')
            ->get();
    }

    private function getNearbyTransitStops($latitude, $longitude)
    {
        return TransitStop::selectRaw("*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance", [$latitude, $longitude, $latitude])
            ->having('distance', '<', 1)
            ->orderBy('distance')
            ->get();
    }

}

