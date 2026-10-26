<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('search_histories', function (Blueprint $table) {
            $table->json('filters')->nullable()->after('results_count');
            $table->foreignId('cafe_id')->nullable()->after('filters')->constrained()->nullOnDelete();
            $table->index(['user_id', 'cafe_id']);
        });
    }

    public function down(): void
    {
        Schema::table('search_histories', function (Blueprint $table) {
            $table->dropIndex(['search_histories_user_id_cafe_id_index']);
            $table->dropForeign(['cafe_id']);
            $table->dropColumn('cafe_id');
            $table->dropColumn('filters');
        });
    }
};
