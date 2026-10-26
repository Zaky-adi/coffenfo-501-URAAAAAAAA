<?php

namespace Database\Seeders;

use App\Models\BeanType;
use Illuminate\Database\Seeder;

class BeanTypeSeeder extends Seeder
{
    public function run(): void
    {
        $beans = [
            ['name' => 'Arabika', 'slug' => 'arabika', 'description' => 'Biji kopi dengan cita rasa kompleks dan keasaman lembut.'],
            ['name' => 'Robusta', 'slug' => 'robusta', 'description' => 'Biji kopi dengan kafein tinggi dan rasa pahit kuat.'],
            ['name' => 'Blend', 'slug' => 'blend', 'description' => 'Campuran beberapa jenis biji kopi.'],
            ['name' => 'Liberika', 'slug' => 'liberika', 'description' => 'Biji kopi khas Kepulauan Riau dengan aroma unik.'],
        ];

        foreach ($beans as $bean) {
            BeanType::firstOrCreate(['slug' => $bean['slug']], $bean);
        }
    }
}
