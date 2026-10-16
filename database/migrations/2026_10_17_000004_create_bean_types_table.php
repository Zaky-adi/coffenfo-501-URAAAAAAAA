<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bean_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('cafe_beans', function (Blueprint $table) {
            $table->foreignId('cafe_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bean_type_id')->constrained()->cascadeOnDelete();
            $table->primary(['cafe_id', 'bean_type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cafe_beans');
        Schema::dropIfExists('bean_types');
    }
};
