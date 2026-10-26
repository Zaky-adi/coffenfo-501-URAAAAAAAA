<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use Illuminate\Database\Seeder;

class MenuCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Coffee', 'slug' => 'coffee', 'icon' => 'coffee', 'sort_order' => 1],
            ['name' => 'No Coffee', 'slug' => 'no-coffee', 'icon' => 'cup', 'sort_order' => 2],
            ['name' => 'Cemilan', 'slug' => 'cemilan', 'icon' => 'snack', 'sort_order' => 3],
            ['name' => 'Makanan Berat', 'slug' => 'makanan-berat', 'icon' => 'bowl', 'sort_order' => 4],
        ];

        foreach ($categories as $category) {
            MenuCategory::firstOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
