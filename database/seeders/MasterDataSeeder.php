<?php

namespace Database\Seeders;

use App\Models\BeanType;
use App\Models\Facility;
use App\Models\MenuCategory;
use App\Models\ServingMethod;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Coffee', 'slug' => 'coffee', 'sort_order' => 1],
            ['name' => 'Non-Coffee', 'slug' => 'no-coffee', 'sort_order' => 2],
            ['name' => 'Cemilan', 'slug' => 'cemilan', 'sort_order' => 3],
            ['name' => 'Makanan Berat', 'slug' => 'makanan-berat', 'sort_order' => 4],
            ['name' => 'Minuman Lain', 'slug' => 'minuman-lain', 'sort_order' => 5],
        ];
        foreach ($categories as $c) {
            MenuCategory::firstOrCreate(['slug' => $c['slug']], $c);
        }

        $beans = [
            ['name' => 'Arabika', 'slug' => 'arabika'],
            ['name' => 'Robusta', 'slug' => 'robusta'],
            ['name' => 'Liberika', 'slug' => 'liberika'],
            ['name' => 'Excelsa', 'slug' => 'excelsa'],
            ['name' => 'Blend', 'slug' => 'blend'],
        ];
        foreach ($beans as $b) {
            BeanType::firstOrCreate(['slug' => $b['slug']], $b);
        }

        $facilities = [
            ['name' => 'WiFi Gratis', 'slug' => 'wifi'],
            ['name' => 'AC', 'slug' => 'ac'],
            ['name' => 'Parkir Luas', 'slug' => 'parkir-luas'],
            ['name' => 'Stop Kontak', 'slug' => 'power-outlet'],
            ['name' => 'Musholla', 'slug' => 'musala'],
            ['name' => 'Area Outdoor', 'slug' => 'outdoor-area'],
            ['name' => 'Smoking Area', 'slug' => 'smoking-area'],
            ['name' => 'Non-Smoking Area', 'slug' => 'non-smoking-area'],
            ['name' => 'Toilet Bersih', 'slug' => 'toilet'],
            ['name' => 'Kamera CCTV', 'slug' => 'cctv'],
            ['name' => 'Reservasi', 'slug' => 'reservasi'],
            ['name' => 'Pembayaran QRIS', 'slug' => 'qris'],
            ['name' => 'Bisa Bawa Hewan', 'slug' => 'pet-friendly'],
            ['name' => 'Ruang Meeting', 'slug' => 'meeting-room'],
            ['name' => 'Live Music', 'slug' => 'live-music'],
        ];
        foreach ($facilities as $f) {
            Facility::firstOrCreate(['slug' => $f['slug']], $f);
        }

        $servings = [
            ['name' => 'Espresso Machine', 'slug' => 'espresso-machine'],
            ['name' => 'Manual Brew', 'slug' => 'manual-brew'],
            ['name' => 'Cold Brew', 'slug' => 'cold-brew'],
            ['name' => 'Kopi Susu', 'slug' => 'kopi-susu'],
            ['name' => 'Tubruk', 'slug' => 'tubruk'],
            ['name' => 'French Press', 'slug' => 'french-press'],
            ['name' => 'Aeropress', 'slug' => 'aeropress'],
            ['name' => 'V60', 'slug' => 'v60'],
            ['name' => 'Siphon', 'slug' => 'siphon'],
            ['name' => 'Moka Pot', 'slug' => 'moka-pot'],
        ];
        foreach ($servings as $s) {
            ServingMethod::firstOrCreate(['slug' => $s['slug']], $s);
        }
    }
}
