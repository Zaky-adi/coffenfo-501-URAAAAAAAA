<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique(); // coffee, no-coffee, cemilan, makanan-berat
            $table->string('icon')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cafe_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_category_id')->constrained();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('price');
            $table->string('photo')->nullable();
            $table->boolean('is_available')->default(true);
            $table->json('meta')->nullable(); // ekstensi fleksibel ke depannya
            $table->timestamps();
            $table->softDeletes();

            $table->index(['cafe_id', 'menu_category_id']);
            $table->index('price'); // filter rentang harga menu
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
        Schema::dropIfExists('menu_categories');
    }
};
