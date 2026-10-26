<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_option_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // Topping, Size, Sugar Level, dll
            $table->boolean('is_required')->default(false);
            $table->unsignedInteger('max_select')->default(1);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('menu_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_option_group_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedBigInteger('price_add')->default(0);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_options');
        Schema::dropIfExists('menu_option_groups');
    }
};
