<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('reviews') && !Schema::hasColumn('reviews', 'status')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->enum('status', ['pending', 'approved', 'reported', 'rejected'])->default('approved')->after('comment');
            });
        }

        if (DB::getDriverName() === 'mysql') {
            DB::unprepared('DROP PROCEDURE IF EXISTS sp_recalc_cafe_rating');
            DB::unprepared("
                CREATE PROCEDURE sp_recalc_cafe_rating(IN p_cafe_id BIGINT UNSIGNED)
                BEGIN
                    UPDATE cafes c
                    LEFT JOIN (
                        SELECT cafe_id, ROUND(AVG(rating), 2) AS avg_r, COUNT(*) AS cnt
                        FROM reviews
                        WHERE cafe_id = p_cafe_id AND status IN ('approved','reported')
                        GROUP BY cafe_id
                    ) r ON r.cafe_id = c.id
                    SET c.avg_rating   = COALESCE(r.avg_r, 0),
                        c.review_count = COALESCE(r.cnt, 0)
                    WHERE c.id = p_cafe_id;
                END
            ");

            DB::unprepared('DROP VIEW IF EXISTS v_cafe_rating_distribution');
            DB::unprepared("
                CREATE VIEW v_cafe_rating_distribution AS
                SELECT cafe_id, rating, COUNT(*) AS total
                FROM reviews
                WHERE status IN ('approved','reported')
                GROUP BY cafe_id, rating
            ");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::unprepared('DROP VIEW IF EXISTS v_cafe_rating_distribution');
            DB::unprepared('DROP PROCEDURE IF EXISTS sp_recalc_cafe_rating');
        }
    }
};
