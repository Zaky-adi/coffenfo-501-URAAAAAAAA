<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'public_id')) {
                $table->char('public_id', 36)->nullable()->after('id');
            }
            if (!Schema::hasColumn('reviews', 'status')) {
                $table->enum('status', ['pending', 'approved', 'reported', 'rejected'])->default('approved')->after('comment');
            }
            if (!Schema::hasColumn('reviews', 'rejection_reason')) {
                $table->string('rejection_reason', 255)->nullable()->after('status');
            }
            if (!Schema::hasColumn('reviews', 'moderated_by')) {
                $table->foreignId('moderated_by')->nullable()->after('rejection_reason')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('reviews', 'moderated_at')) {
                $table->dateTime('moderated_at')->nullable()->after('moderated_by');
            }
            if (!Schema::hasColumn('reviews', 'has_photo')) {
                $table->boolean('has_photo')->default(false)->after('moderated_at');
            }
            if (!Schema::hasColumn('reviews', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('has_photo');
            }
        });

        // Backfill UUID untuk record yang belum punya public_id
        $reviews = DB::table('reviews')->whereNull('public_id')->get();
        foreach ($reviews as $rev) {
            DB::table('reviews')->where('id', $rev->id)->update([
                'public_id' => Str::uuid()->toString(),
            ]);
        }

        Schema::table('reviews', function (Blueprint $table) {
            try {
                $table->char('public_id', 36)->nullable(false)->unique('uq_reviews_public_id')->change();
            } catch (\Throwable $e) {
            }
            try {
                $table->index(['cafe_id', 'status', 'created_at'], 'idx_reviews_cafe_list');
            } catch (\Throwable $e) {
            }
            try {
                $table->index(['cafe_id', 'status', 'rating'], 'idx_reviews_cafe_rating');
            } catch (\Throwable $e) {
            }
            try {
                $table->index(['status', 'created_at'], 'idx_reviews_queue');
            } catch (\Throwable $e) {
            }
            try {
                $table->index(['is_featured', 'status'], 'idx_reviews_featured');
            } catch (\Throwable $e) {
            }
        });

        Schema::create('review_helpful_votes', function (Blueprint $table) {
            $table->foreignId('review_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->primary(['review_id', 'user_id']);
            $table->index('user_id', 'idx_helpful_user');
        });

        Schema::create('review_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reporter_user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('reason', ['spam', 'offensive', 'irrelevant', 'inappropriate', 'other']);
            $table->string('note', 300)->nullable();
            $table->enum('status', ['open', 'resolved', 'dismissed'])->default('open');
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('handled_at')->nullable();
            $table->timestamps();
            $table->unique(['review_id', 'reporter_user_id'], 'uq_report_once');
            $table->index(['status', 'created_at'], 'idx_reports_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_reports');
        Schema::dropIfExists('review_helpful_votes');

        Schema::table('reviews', function (Blueprint $table) {
            try {
                $table->dropForeign(['moderated_by']);
            } catch (\Throwable $e) {
            }
            try {
                $table->dropIndex('idx_reviews_cafe_list');
                $table->dropIndex('idx_reviews_cafe_rating');
                $table->dropIndex('idx_reviews_queue');
                $table->dropIndex('idx_reviews_featured');
                $table->dropUnique('uq_reviews_public_id');
            } catch (\Throwable $e) {
            }
            $cols = ['public_id', 'status', 'rejection_reason', 'moderated_by', 'moderated_at', 'has_photo', 'is_featured'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('reviews', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
