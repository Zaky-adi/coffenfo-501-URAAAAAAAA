<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cafes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('address');
            $table->string('city')->default('Batam');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('phone', 20)->nullable();
            $table->string('price_range', 10)->default('mid')->index(); // low, mid, high
            $table->decimal('avg_rating', 3, 2)->default(0)->index();   // cache rating agregat
            $table->unsignedInteger('review_count')->default(0);        // cache jumlah ulasan
            $table->string('cover_photo')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->json('meta')->nullable(); // ekstensi fleksibel ke depannya
            $table->timestamps();
            $table->softDeletes();

            $table->index(['latitude', 'longitude']); // query nearby (Haversine)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cafes');
    }
};
