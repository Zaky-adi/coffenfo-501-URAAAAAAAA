<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cafes', function (Blueprint $table) {
            $table->index(['is_active', 'avg_rating']);
            $table->index(['is_active', 'review_count']);
            $table->index(['city', 'is_active']);
        });

        Schema::table('visits', function (Blueprint $table) {
            $table->index(['user_id', 'cafe_id', 'visited_at']);
        });

        Schema::table('search_histories', function (Blueprint $table) {
            $table->index(['user_id', 'created_at', 'id']);
        });

        Schema::table('wishlists', function (Blueprint $table) {
            $table->index(['user_id', 'created_at']);
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->index(['cafe_id', 'is_available', 'price']);
        });
    }

    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropIndex(['menus_cafe_id_is_available_price_index']);
        });

        Schema::table('wishlists', function (Blueprint $table) {
            $table->dropIndex(['wishlists_user_id_created_at_index']);
        });

        Schema::table('search_histories', function (Blueprint $table) {
            $table->dropIndex(['search_histories_user_id_created_at_id_index']);
        });

        Schema::table('visits', function (Blueprint $table) {
            $table->dropIndex(['visits_user_id_cafe_id_visited_at_index']);
        });

        Schema::table('cafes', function (Blueprint $table) {
            $table->dropIndex(['cafes_is_active_avg_rating_index']);
            $table->dropIndex(['cafes_is_active_review_count_index']);
            $table->dropIndex(['cafes_city_is_active_index']);
        });
    }
};
