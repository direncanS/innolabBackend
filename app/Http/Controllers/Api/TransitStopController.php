<?php

namespace App\Http\Controllers\Api;

use App\Models\TransitStop;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransitStopController extends Controller
{
    public function index()
    {
        return TransitStop::all();
    }

    public function show($id)
    {
        return TransitStop::findOrFail($id);
    }

    public function nearby(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $radius = $request->input('radius', 5); // Varsayılan yarıçap 5 km

        return TransitStop::selectRaw("*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) )
            * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance",
            [$latitude, $longitude, $latitude])
            ->having('distance', '<', $radius)
            ->orderBy('distance')
            ->get();
    }
}
