<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cafe_id')->constrained()->cascadeOnDelete();
            $table->foreignId('visit_id')->nullable()->constrained()->nullOnDelete(); // syarat: pernah check-in
            $table->unsignedTinyInteger('rating')->index(); // 1-5 bintang
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'cafe_id']); // 1 review per user per kafe (boleh diedit)
            $table->index(['cafe_id', 'rating']);   // filter berdasarkan rating
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
