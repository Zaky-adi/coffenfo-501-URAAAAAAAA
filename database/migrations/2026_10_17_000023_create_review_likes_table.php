<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('review_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['review_id', 'user_id']);
            $table->index(['user_id', 'created_at']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedInteger('helpful_count')->default(0)->after('comment');
            $table->boolean('is_anonymous')->default(false)->after('helpful_count');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('is_anonymous');
            $table->dropColumn('helpful_count');
        });

        Schema::dropIfExists('review_likes');
    }
};
