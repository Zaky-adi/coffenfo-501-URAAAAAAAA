<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_cafe_views', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('cafe_id')->constrained('cafes')->cascadeOnDelete();
            $table->unsignedInteger('view_count')->default(1);
            $table->timestamp('first_viewed_at')->useCurrent();
            $table->timestamp('last_viewed_at')->useCurrent();

            $table->primary(['user_id', 'cafe_id']);
            $table->index(['user_id', 'last_viewed_at'], 'idx_views_user_recent');
        });

        Schema::create('user_search_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('keyword', 100);
            $table->foreignId('cafe_id')->nullable()->constrained('cafes')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id', 'created_at'], 'idx_search_user_recent');
        });

        // Migrasi data search history lama jika ada
        if (Schema::hasTable('search_histories')) {
            $histories = DB::table('search_histories')->get();
            foreach ($histories as $h) {
                DB::table('user_search_history')->insert([
                    'user_id' => $h->user_id,
                    'keyword' => substr($h->keyword, 0, 100),
                    'cafe_id' => $h->cafe_id ?? null,
                    'created_at' => $h->created_at ?? now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_search_history');
        Schema::dropIfExists('user_cafe_views');
    }
};

