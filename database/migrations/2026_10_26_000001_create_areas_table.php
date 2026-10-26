<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 80)->unique();
            $table->string('slug', 80)->unique();
            $table->decimal('latitude', 9, 6)->nullable();
            $table->decimal('longitude', 9, 6)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('areas')->insert([
            ['name' => 'Batam Center', 'slug' => 'batam-center', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Nagoya', 'slug' => 'nagoya', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Jodoh', 'slug' => 'jodoh', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Batu Ampar', 'slug' => 'batu-ampar', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lubuk Baja', 'slug' => 'lubuk-baja', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sekupang', 'slug' => 'sekupang', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Batu Aji', 'slug' => 'batu-aji', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sagulung', 'slug' => 'sagulung', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bengkong', 'slug' => 'bengkong', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tiban', 'slug' => 'tiban', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Nongsa', 'slug' => 'nongsa', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tanjung Uncang', 'slug' => 'tanjung-uncang', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};
