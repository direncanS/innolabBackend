<?php

namespace App\Http\Controllers\Api;

use App\Models\Library;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    public function index()
    {
        return Library::all();
    }

    public function show($id)
    {
        return Library::findOrFail($id);
    }

    public function nearby(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $radius = $request->input('radius', 5);

        return Library::selectRaw("*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) )
            * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance",
            [$latitude, $longitude, $latitude])
            ->having('distance', '<', $radius)
            ->orderBy('distance')
            ->get();
    }
}
