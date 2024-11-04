<?php

namespace App\Http\Controllers\Api;

use App\Models\GreenArea;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GreenAreaController extends Controller
{

    public function index()
    {
        return GreenArea::all();
    }

    public function show($id)
    {
        return GreenArea::findOrFail($id);
    }

    public function nearby(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $radius = $request->input('radius', 5);

        return GreenArea::selectRaw("*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) )
            * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance",
            [$latitude, $longitude, $latitude])
            ->having('distance', '<', $radius)
            ->orderBy('distance')
            ->get();
    }
}
