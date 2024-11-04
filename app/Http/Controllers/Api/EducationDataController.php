<?php

namespace App\Http\Controllers\Api;

use App\Models\EducationData;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EducationDataController extends Controller
{
    public function index()
    {
        return EducationData::all();
    }

    public function show($id)
    {
        return EducationData::findOrFail($id);
    }

    public function nearby(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $radius = $request->input('radius', 5); // Varsayılan yarıçap 5 km

        return EducationData::selectRaw("*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) )
            * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance",
            [$latitude, $longitude, $latitude])
            ->having('distance', '<', $radius)
            ->orderBy('distance')
            ->get();
    }
}
