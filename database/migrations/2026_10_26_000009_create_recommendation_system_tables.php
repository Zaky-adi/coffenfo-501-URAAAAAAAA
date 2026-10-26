<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_privacy_settings', function (Blueprint $table) {
            $table->foreignId('user_id')->primary()->constrained()->cascadeOnDelete();
            $table->boolean('personalization_enabled')->default(false);
            $table->boolean('location_enabled')->default(false);
            $table->string('consent_version', 20)->nullable();
            $table->dateTime('consented_at')->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('user_locations', function (Blueprint $table) {
            $table->foreignId('user_id')->primary()->constrained()->cascadeOnDelete();
            $table->decimal('latitude', 9, 6);
            $table->decimal('longitude', 9, 6);
            $table->unsignedSmallInteger('area_id')->nullable();
            $table->foreign('area_id')->references('id')->on('areas')->nullOnDelete();
            $table->unsignedSmallInteger('accuracy_m')->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('user_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('event_type', ['impression', 'view', 'click', 'search', 'search_click', 'wishlist_add', 'wishlist_remove', 'directions_click', 'share', 'dismiss', 'review', 'order']);
            $table->foreignId('cafe_id')->nullable()->constrained()->nullOnDelete();
            $table->string('query_text', 100)->nullable();
            $table->string('surface', 30)->nullable();
            $table->unsignedTinyInteger('position')->nullable();
            $table->char('rec_request_id', 36)->nullable();
            $table->string('model_version', 20)->nullable();
            $table->decimal('value_num', 4, 2)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['user_id', 'created_at']);
            $table->index(['user_id', 'event_type', 'created_at']);
            $table->index(['cafe_id', 'event_type', 'created_at']);
            $table->index('rec_request_id');
        });

        Schema::create('user_taste_weights', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('feature_type', ['bean_type', 'roast_level', 'brew_method', 'species', 'facility', 'area', 'price_range', 'menu_category', 'keyword']);
            $table->string('feature_key', 60);
            $table->decimal('weight', 8, 4);
            $table->unsignedSmallInteger('evidence_count')->default(0);
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->primary(['user_id', 'feature_type', 'feature_key']);
            $table->index(['user_id', 'weight']);
        });

        Schema::create('cafe_similarity', function (Blueprint $table) {
            $table->foreignId('cafe_id')->constrained()->cascadeOnDelete();
            $table->foreignId('similar_cafe_id')->constrained('cafes')->cascadeOnDelete();
            $table->decimal('score', 6, 5);
            $table->enum('method', ['content', 'collab', 'hybrid'])->default('hybrid');
            $table->timestamp('computed_at')->useCurrent();
            $table->primary(['cafe_id', 'similar_cafe_id']);
            $table->index(['cafe_id', 'score']);
        });

        Schema::create('user_cafe_scores', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cafe_id')->constrained()->cascadeOnDelete();
            $table->decimal('score', 7, 5);
            $table->string('reason_code', 40)->nullable();
            $table->string('reason_ref', 60)->nullable();
            $table->string('model_version', 20);
            $table->timestamp('computed_at')->useCurrent();
            $table->primary(['user_id', 'cafe_id']);
            $table->index(['user_id', 'score']);
        });

        Schema::create('user_hidden_cafes', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cafe_id')->constrained()->cascadeOnDelete();
            $table->enum('reason', ['not_interested', 'closed', 'bad_experience', 'other'])->default('not_interested');
            $table->timestamp('created_at')->useCurrent();
            $table->primary(['user_id', 'cafe_id']);
        });

        Schema::create('reco_model_versions', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('version', 20)->unique();
            $table->string('description', 200)->nullable();
            $table->json('params');
            $table->unsignedTinyInteger('traffic_percent')->default(100);
            $table->boolean('is_active')->default(false);
            $table->timestamp('created_at')->useCurrent();
        });

        DB::table('reco_model_versions')->insert([
            [
                'version' => 'v1-content',
                'description' => 'Tahap B: selera + jarak + populer + mirip yang disukai',
                'params' => json_encode([
                    "half_life_days" => 30,
                    "event_weights" => ["view" => 1, "click" => 2, "search" => 1, "search_click" => 2, "directions_click" => 4, "share" => 3, "wishlist_add" => 5, "wishlist_remove" => -3, "review_pos" => 6, "review_neg" => -4, "order" => 8, "dismiss" => -6, "impression" => -0.05],
                    "feature_weights" => ["brew_method" => 0.25, "bean_type" => 0.20, "roast_level" => 0.20, "facility" => 0.15, "price_range" => 0.10, "area" => 0.10],
                    "score_weights" => ["taste" => 0.40, "proximity" => 0.25, "popularity" => 0.15, "similar_to_liked" => 0.15, "freshness" => 0.05],
                    "cold_start_score_weights" => ["taste" => 0.15, "proximity" => 0.35, "popularity" => 0.40, "similar_to_liked" => 0.05, "freshness" => 0.05],
                    "cold_start_min_events" => 10,
                    "distance_decay_km" => 3,
                    "explore_ratio" => 0.10,
                    "diversity_lambda" => 0.7,
                    "max_events_per_cafe_per_day" => 3,
                    "candidate_pool_size" => 100
                ]),
                'traffic_percent' => 100,
                'is_active' => true,
                'created_at' => now(),
            ],
        ]);

        DB::statement('INSERT IGNORE INTO user_privacy_settings (user_id) SELECT id FROM users');

        if (DB::getDriverName() === 'mysql') {
            DB::statement("CREATE EVENT IF NOT EXISTS ev_purge_user_events ON SCHEDULE EVERY 1 HOUR DO DELETE FROM user_events WHERE created_at < UTC_TIMESTAMP() - INTERVAL 90 DAY LIMIT 50000");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('DROP EVENT IF EXISTS ev_purge_user_events');
        }
        Schema::dropIfExists('reco_model_versions');
        Schema::dropIfExists('user_hidden_cafes');
        Schema::dropIfExists('user_cafe_scores');
        Schema::dropIfExists('cafe_similarity');
        Schema::dropIfExists('user_taste_weights');
        Schema::dropIfExists('user_events');
        Schema::dropIfExists('user_locations');
        Schema::dropIfExists('user_privacy_settings');
    }
};
