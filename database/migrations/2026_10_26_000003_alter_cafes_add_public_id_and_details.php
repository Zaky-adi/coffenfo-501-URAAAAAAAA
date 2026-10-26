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
        Schema::table('cafes', function (Blueprint $table) {
            if (!Schema::hasColumn('cafes', 'public_id')) {
                $table->char('public_id', 36)->nullable()->after('id');
            }
            if (!Schema::hasColumn('cafes', 'area_id')) {
                $table->unsignedSmallInteger('area_id')->nullable()->after('address');
            }
            if (!Schema::hasColumn('cafes', 'tagline')) {
                $table->string('tagline', 160)->nullable()->after('slug');
            }
            if (!Schema::hasColumn('cafes', 'coffee_note')) {
                $table->text('coffee_note')->nullable()->after('description');
            }
            if (!Schema::hasColumn('cafes', 'price_range_new')) {
                $table->tinyInteger('price_range_new')->unsigned()->default(2)->after('phone');
            }
            if (!Schema::hasColumn('cafes', 'has_self_roasting')) {
                $table->boolean('has_self_roasting')->default(false)->after('price_range_new');
            }
            if (!Schema::hasColumn('cafes', 'accepts_orders')) {
                $table->boolean('accepts_orders')->default(false)->after('has_self_roasting');
            }
            if (!Schema::hasColumn('cafes', 'status')) {
                $table->enum('status', ['draft', 'pending', 'active', 'suspended'])->default('draft')->after('is_active');
            }
        });

        $cafes = DB::table('cafes')->get();
        foreach ($cafes as $cafe) {
            DB::table('cafes')->where('id', $cafe->id)->update([
                'public_id' => Str::uuid()->toString(),
            ]);
        }

        Schema::table('cafes', function (Blueprint $table) {
            $table->char('public_id', 36)->nullable(false)->unique()->change();
            $table->unsignedSmallInteger('area_id')->nullable(false)->change();
            try {
                $table->foreign('area_id')->references('id')->on('areas')->restrictOnDelete();
            } catch (\Throwable $e) {
            }
            try {
                $table->index(['status', 'area_id']);
            } catch (\Throwable $e) {
            }
            try {
                $table->index(['avg_rating', 'review_count']);
            } catch (\Throwable $e) {
            }
        });
    }

    public function down(): void
    {
        Schema::table('cafes', function (Blueprint $table) {
            $table->dropForeign(['area_id']);
            $table->dropIndex(['cafes_status_area_id_index']);
            $table->dropIndex(['cafes_latitude_longitude_index']);
            $table->dropIndex(['cafes_avg_rating_review_count_index']);
            $table->dropUnique(['cafes_public_id_unique']);
            $table->dropColumn(['public_id', 'area_id', 'tagline', 'coffee_note', 'price_range_new', 'has_self_roasting', 'accepts_orders', 'status']);
        });
    }
};
