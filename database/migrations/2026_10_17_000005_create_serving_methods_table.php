<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('serving_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('cafe_serving_methods', function (Blueprint $table) {
            $table->foreignId('cafe_id')->constrained()->cascadeOnDelete();
            $table->foreignId('serving_method_id')->constrained()->cascadeOnDelete();
            $table->primary(['cafe_id', 'serving_method_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cafe_serving_methods');
        Schema::dropIfExists('serving_methods');
    }
};
