<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['visit_id']);
        });

        DB::statement('
            UPDATE reviews r
            INNER JOIN (
                SELECT user_id, cafe_id, MAX(id) as visit_id_latest
                FROM visits
                GROUP BY user_id, cafe_id
            ) v ON r.user_id = v.user_id AND r.cafe_id = v.cafe_id
            SET r.visit_id = v.visit_id_latest
            WHERE r.visit_id IS NULL
        ');

        DB::statement('DELETE FROM reviews WHERE visit_id IS NULL');

        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('visit_id')->nullable(false)->change();
            $table->foreign('visit_id')->references('id')->on('visits')->cascadeOnDelete();
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['visit_id']);
            $table->dropIndex(['reviews_user_id_created_at_index']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('visit_id')->nullable()->change();
            $table->foreign('visit_id')->references('id')->on('visits')->nullOnDelete();
        });
    }
};
