<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type', 40);
            $table->string('title', 120);
            $table->string('body', 300)->nullable();
            $table->json('data')->nullable();
            $table->dateTime('read_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['user_id', 'read_at', 'created_at']);
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actor_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('actor_role', 20)->nullable();
            $table->string('action', 60);
            $table->string('entity_type', 40);
            $table->string('entity_id', 40)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['actor_user_id', 'created_at']);
            $table->index(['entity_type', 'entity_id']);
            $table->index(['action', 'created_at']);
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('CREATE TRIGGER trg_audit_no_update BEFORE UPDATE ON audit_logs FOR EACH ROW BEGIN SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "audit_logs bersifat append-only"; END');
            DB::statement('CREATE TRIGGER trg_audit_no_delete BEFORE DELETE ON audit_logs FOR EACH ROW BEGIN SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "audit_logs bersifat append-only"; END');
        }

        Schema::create('app_settings', function (Blueprint $table) {
            $table->string('setting_key', 60)->primary();
            $table->string('setting_value', 255);
            $table->string('description', 200)->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        DB::table('app_settings')->insert([
            ['setting_key' => 'login_max_attempts', 'setting_value' => '5', 'description' => 'Gagal login berturut-turut sebelum akun dikunci'],
            ['setting_key' => 'login_lockout_minutes', 'setting_value' => '15', 'description' => 'Lama akun dikunci (menit)'],
            ['setting_key' => 'review_min_comment_length', 'setting_value' => '30', 'description' => 'Panjang minimal komentar untuk poin komentar'],
            ['setting_key' => 'review_max_length', 'setting_value' => '500', 'description' => 'Panjang maksimal komentar'],
            ['setting_key' => 'review_max_photos', 'setting_value' => '5', 'description' => 'Maksimal foto per review'],
            ['setting_key' => 'review_report_hide_threshold', 'setting_value' => '3', 'description' => 'Jumlah laporan berbeda yang menyembunyikan review sementara'],
            ['setting_key' => 'voucher_default_validity_days', 'setting_value' => '30', 'description' => 'Masa berlaku default voucher setelah ditukar'],
            ['setting_key' => 'service_fee_flat', 'setting_value' => '2500', 'description' => 'Biaya layanan (rupiah)'],
            ['setting_key' => 'order_expire_minutes', 'setting_value' => '30', 'description' => 'Batas waktu bayar sebelum pesanan kedaluwarsa'],
        ]);
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('DROP TRIGGER IF EXISTS trg_audit_no_update');
            DB::statement('DROP TRIGGER IF EXISTS trg_audit_no_delete');
        }
        Schema::dropIfExists('app_settings');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('notifications');
    }
};
