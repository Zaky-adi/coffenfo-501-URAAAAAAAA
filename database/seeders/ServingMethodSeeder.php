<?php

namespace Database\Seeders;

use App\Models\ServingMethod;
use Illuminate\Database\Seeder;

class ServingMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            ['name' => 'Espresso Machine', 'slug' => 'espresso-machine', 'description' => 'Mesin espresso standar kafe modern.'],
            ['name' => 'Manual Brew', 'slug' => 'manual-brew', 'description' => 'Seduh manual seperti V60, Chemex, atau AeroPress.'],
            ['name' => 'Tubruk', 'slug' => 'tubruk', 'description' => 'Cara seduh klasik Indonesia dengan air panas langsung.'],
            ['name' => 'Kopi Susu', 'slug' => 'kopi-susu', 'description' => 'Espresso dengan susu segar, khas kafe Indonesia.'],
            ['name' => 'Cold Brew', 'slug' => 'cold-brew', 'description' => 'Rendam biji kopi dingin berjam-jam.'],
        ];

        foreach ($methods as $method) {
            ServingMethod::firstOrCreate(['slug' => $method['slug']], $method);
        }
    }
}
