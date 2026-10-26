<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->char('public_id', 36)->unique();
            $table->foreignId('cafe_id')->constrained('cafes')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('menu_categories')->restrictOnDelete();
            $table->string('name', 150);
            $table->string('description', 300)->nullable();
            $table->unsignedInteger('price');
            $table->string('image_url', 500)->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['cafe_id', 'category_id', 'is_active', 'sort_order'], 'idx_menu_cafe_cat');
        });

        if (DB::getDriverName() === 'mysql') {
            try {
                DB::statement('ALTER TABLE menu_items ADD CONSTRAINT chk_menu_price CHECK (price > 0)');
                DB::statement('ALTER TABLE menu_items ADD FULLTEXT ft_menu_name (name)');
            } catch (\Throwable $e) {
            }
        }

        // Migrasi data menu lama jika tabel menus ada
        if (Schema::hasTable('menus')) {
            $existingMenus = DB::table('menus')->get();
            foreach ($existingMenus as $m) {
                DB::table('menu_items')->insert([
                    'public_id' => Str::uuid()->toString(),
                    'cafe_id' => $m->cafe_id,
                    'category_id' => $m->menu_category_id,
                    'name' => $m->name,
                    'description' => $m->description,
                    'price' => $m->price > 0 ? $m->price : 1000,
                    'image_url' => $m->photo ?? null,
                    'is_available' => $m->is_available ?? true,
                    'is_active' => $m->deleted_at === null,
                    'sort_order' => 0,
                    'created_at' => $m->created_at ?? now(),
                    'updated_at' => $m->updated_at ?? now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};

