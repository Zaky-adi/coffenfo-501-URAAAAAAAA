<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('point_rules', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('code', 30)->unique();
            $table->unsignedSmallInteger('points');
            $table->string('description', 150)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('point_rules')->insert([
            ['code' => 'review_rating', 'points' => 5, 'description' => 'Memberi rating bintang', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'review_comment', 'points' => 10, 'description' => 'Menulis komentar minimal 30 karakter', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'review_photo', 'points' => 15, 'description' => 'Mengunggah minimal 1 foto', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        Schema::create('user_points', function (Blueprint $table) {
            $table->foreignId('user_id')->primary()->constrained()->restrictOnDelete();
            $table->unsignedInteger('balance')->default(0);
            $table->unsignedInteger('lifetime_earned')->default(0);
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('points_ledger', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->enum('entry_type', ['earn', 'redeem', 'refund', 'revoke', 'adjust']);
            $table->integer('points');
            $table->unsignedInteger('balance_after');
            $table->enum('source_type', ['review', 'voucher', 'order', 'admin']);
            $table->unsignedBigInteger('source_id')->nullable();
            $table->string('idempotency_key', 100)->unique();
            $table->string('note', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['user_id', 'created_at']);
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('CREATE TRIGGER trg_ledger_no_update BEFORE UPDATE ON points_ledger FOR EACH ROW BEGIN SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "points_ledger bersifat append-only"; END');
            DB::statement('CREATE TRIGGER trg_ledger_no_delete BEFORE DELETE ON points_ledger FOR EACH ROW BEGIN SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "points_ledger bersifat append-only"; END');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('DROP TRIGGER IF EXISTS trg_ledger_no_update');
            DB::statement('DROP TRIGGER IF EXISTS trg_ledger_no_delete');
        }
        Schema::dropIfExists('points_ledger');
        Schema::dropIfExists('user_points');
        Schema::dropIfExists('point_rules');
    }
};
