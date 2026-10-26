<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cafe_hours_exceptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cafe_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->boolean('is_closed')->default(false);
            $table->string('reason')->nullable();
            $table->timestamps();

            $table->unique(['cafe_id', 'date']);
            $table->index(['date', 'is_closed']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cafe_hours_exceptions');
    }
};
