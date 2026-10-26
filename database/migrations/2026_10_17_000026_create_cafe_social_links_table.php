<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cafe_social_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cafe_id')->constrained()->cascadeOnDelete();
            $table->string('platform', 30); // instagram, tiktok, whatsapp, website, maps, etc
            $table->string('url');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['cafe_id', 'platform']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cafe_social_links');
    }
};
