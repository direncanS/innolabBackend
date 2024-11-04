<?php

namespace App\Http\Controllers\Api;

use App\Models\Kindergarten;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KindergartenController extends Controller
{
    public function index()
    {
        return Kindergarten::all();
    }

    public function show($id)
    {
        return Kindergarten::findOrFail($id);
    }

    public function nearby(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        return Kindergarten::selectRaw("*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance", [$latitude, $longitude, $latitude])
            ->having('distance', '<', 5)  // 5 km yarıçap
            ->orderBy('distance')
            ->get();
    }
}
