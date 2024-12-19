<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AirQuality\Component;
use Illuminate\Support\Facades\Http;

class ComponentSeeder extends Seeder
{
    public function run()
    {
        $response = Http::get('https://www2.land-oberoesterreich.gv.at/imm/jaxrs/komponenten/json');
        $components = $response->json()['komponenten'];

        foreach ($components as $component) {
            Component::updateOrCreate(
<<<<<<< HEAD
                ['code' => $component['code']], // Bu kod zaten varsa güncelle
=======
                ['code' => $component['code']],
>>>>>>> 8dbac16 (last version)
                ['name' => $component['bezeichnung']]
            );
        }
    }
}
