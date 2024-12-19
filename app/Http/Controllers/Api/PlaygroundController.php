<?php

namespace App\Http\Controllers\Api;

use App\Models\Playground;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PlaygroundController extends Controller
{
    public function index()
    {
        return Playground::all();
    }

    public function show($id)
    {
        return Playground::findOrFail($id);
    }

    public function nearby(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $radius = $request->input('radius', 5);

        $playgrounds = Playground::selectRaw(
            "*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) )
            * cos( radians( longitude ) - radians(?) ) + sin( radians(?) )
            * sin( radians( latitude ) ) ) ) AS distance",
            [$latitude, $longitude, $latitude]
        )
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->get();

        return response()->json($playgrounds);
    }

    public function filter(Request $request)
    {
        $type = $request->input('type_detail');
        $district = $request->input('district_code');

        $query = Playground::query();

        if ($type) {
            $query->where('type_detail', 'LIKE', "%{$type}%");
        }

        if ($district) {
            $query->where('district_code', $district);
        }

        return response()->json($query->get());
    }
}
