<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            ['name' => 'Wi-Fi', 'slug' => 'wifi', 'icon' => 'wifi'],
            ['name' => 'Parkir Luas', 'slug' => 'parkir-luas', 'icon' => 'parking'],
            ['name' => 'AC', 'slug' => 'ac', 'icon' => 'snowflake'],
            ['name' => 'Outdoor Area', 'slug' => 'outdoor-area', 'icon' => 'tree'],
            ['name' => 'Power Outlet', 'slug' => 'power-outlet', 'icon' => 'plug'],
            ['name' => 'Musala', 'slug' => 'musala', 'icon' => 'mosque'],
            ['name' => 'Live Music', 'slug' => 'live-music', 'icon' => 'music'],
            ['name' => 'Smoking Area', 'slug' => 'smoking-area', 'icon' => 'smoke'],
            ['name' => 'Toilet Bersih', 'slug' => 'toilet-bersih', 'icon' => 'toilet'],
        ];

        foreach ($facilities as $facility) {
            Facility::firstOrCreate(['slug' => $facility['slug']], $facility);
        }
    }
}
