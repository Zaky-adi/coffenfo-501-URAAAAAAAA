<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cafe_id')->constrained()->cascadeOnDelete();
            $table->decimal('checkin_latitude', 10, 7)->nullable();  // koordinat saat check-in GPS
            $table->decimal('checkin_longitude', 10, 7)->nullable();
            $table->timestamp('visited_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'visited_at']); // "kafe terakhir dikunjungi"
            $table->index(['cafe_id', 'visited_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
