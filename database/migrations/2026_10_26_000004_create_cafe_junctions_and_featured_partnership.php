<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cafe_species', function (Blueprint $table) {
            $table->foreignId('cafe_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('species_id');
            $table->foreign('species_id')->references('id')->on('coffee_species')->restrictOnDelete();
            $table->primary(['cafe_id', 'species_id']);
            $table->index('species_id');
        });

        Schema::create('cafe_roast_levels', function (Blueprint $table) {
            $table->foreignId('cafe_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('roast_level_id');
            $table->foreign('roast_level_id')->references('id')->on('roast_levels')->restrictOnDelete();
            $table->primary(['cafe_id', 'roast_level_id']);
            $table->index('roast_level_id');
        });

        Schema::create('cafe_brew_methods', function (Blueprint $table) {
            $table->foreignId('cafe_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('brew_method_id');
            $table->foreign('brew_method_id')->references('id')->on('brew_methods')->restrictOnDelete();
            $table->primary(['cafe_id', 'brew_method_id']);
            $table->index('brew_method_id');
        });

        Schema::create('featured_cafes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cafe_id')->constrained()->cascadeOnDelete();
            $table->string('label', 60)->nullable();
            $table->date('starts_on');
            $table->date('ends_on');
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['starts_on', 'ends_on']);
        });

        Schema::create('partnership_requests', function (Blueprint $table) {
            $table->id();
            $table->string('cafe_name', 150);
            $table->string('owner_name', 120);
            $table->string('whatsapp', 20);
            $table->unsignedSmallInteger('area_id');
            $table->foreign('area_id')->references('id')->on('areas')->restrictOnDelete();
            $table->enum('status', ['new', 'contacted', 'approved', 'rejected'])->default('new');
            $table->string('admin_note', 500)->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partnership_requests');
        Schema::dropIfExists('featured_cafes');
        Schema::dropIfExists('cafe_brew_methods');
        Schema::dropIfExists('cafe_roast_levels');
        Schema::dropIfExists('cafe_species');
    }
};
