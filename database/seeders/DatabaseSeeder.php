<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            KindergartenSeeder::class,
            TransitStopSeeder::class,
            GreenAreaSeeder::class,
            MeasurementSeeder::class,
            StationSeeder::class,
            ComponentSeeder::class,
            DistrictSeeder::class,
            SubdistrictSeeder::class,
            EducationSeeder::class,
            PolygonSeeder::class,
        ]);
    }
}
