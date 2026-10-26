<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_search_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cafe_id')->constrained()->cascadeOnDelete();
            $table->string('keyword')->nullable();
            $table->json('filters')->nullable();
            $table->timestamp('clicked_at')->useCurrent();
            $table->timestamps();

            $table->index(['user_id', 'cafe_id', 'clicked_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_search_clicks');
    }
};
