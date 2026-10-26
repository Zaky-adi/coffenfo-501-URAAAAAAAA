<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Sesi per perangkat pada refresh_tokens
        Schema::table('refresh_tokens', function (Blueprint $table) {
            if (!Schema::hasColumn('refresh_tokens', 'client_platform')) {
                $table->enum('client_platform', ['web', 'android', 'ios'])->default('web')->after('family_id');
            }
            if (!Schema::hasColumn('refresh_tokens', 'device_id')) {
                $table->string('device_id', 64)->nullable()->after('client_platform');
            }
            if (!Schema::hasColumn('refresh_tokens', 'device_name')) {
                $table->string('device_name', 100)->nullable()->after('device_id');
            }
            if (!Schema::hasColumn('refresh_tokens', 'app_version')) {
                $table->string('app_version', 20)->nullable()->after('device_name');
            }
            if (!Schema::hasColumn('refresh_tokens', 'last_used_at')) {
                $table->dateTime('last_used_at')->nullable()->after('app_version');
            }
            try {
                $table->index(['user_id', 'device_id'], 'idx_refresh_device');
            } catch (\Throwable $e) {
            }
        });

        // 2) Perangkat + token push (FCM)
        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('device_id', 64);
            $table->enum('platform', ['android', 'ios']);
            $table->string('device_name', 100)->nullable();
            $table->string('app_version', 20)->nullable();
            $table->string('locale', 10)->nullable();
            $table->string('push_token', 255)->nullable()->unique('uq_devices_push_token');
            $table->boolean('push_enabled')->default(true);
            $table->timestamp('last_seen_at')->useCurrent();
            $table->timestamps();

            $table->unique(['user_id', 'device_id'], 'uq_devices_user_device');
            $table->index(['user_id', 'push_enabled'], 'idx_devices_user');
        });

        // 3) Kategori & preferensi notifikasi
        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications', 'category')) {
                $table->enum('category', ['review', 'points', 'order', 'recommendation', 'promo', 'system'])->default('system')->after('type');
            }
            if (!Schema::hasColumn('notifications', 'deep_link')) {
                $table->string('deep_link', 255)->nullable()->after('body');
            }
        });

        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('category', ['review', 'points', 'order', 'recommendation', 'promo', 'system']);
            $table->boolean('push_enabled');
            $table->boolean('inapp_enabled')->default(true);
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->primary(['user_id', 'category']);
        });

        Schema::create('push_outbox', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_id')->constrained('notifications')->cascadeOnDelete();
            $table->foreignId('user_device_id')->constrained('user_devices')->cascadeOnDelete();
            $table->enum('status', ['pending', 'sent', 'failed', 'invalid_token', 'skipped'])->default('pending');
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->string('last_error', 255)->nullable();
            $table->timestamp('scheduled_at')->useCurrent();
            $table->dateTime('sent_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['notification_id', 'user_device_id'], 'uq_outbox_notif_device');
            $table->index(['status', 'scheduled_at'], 'idx_outbox_pending');
        });

        // 4) Unggah foto lewat presigned URL
        Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            $table->char('public_id', 36)->unique('uq_uploads_public_id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('purpose', ['review_photo', 'cafe_photo', 'menu_photo', 'avatar']);
            $table->string('object_key', 255)->unique('uq_uploads_object_key');
            $table->string('content_type', 50);
            $table->unsignedInteger('declared_size');
            $table->unsignedInteger('size_bytes')->nullable();
            $table->unsignedSmallInteger('width')->nullable();
            $table->unsignedSmallInteger('height')->nullable();
            $table->enum('status', ['pending', 'processing', 'ready', 'rejected', 'expired'])->default('pending');
            $table->string('rejection_reason', 100)->nullable();
            $table->string('url', 500)->nullable();
            $table->dateTime('consumed_at')->nullable();
            $table->dateTime('expires_at');
            $table->timestamp('created_at')->useCurrent();
            $table->dateTime('confirmed_at')->nullable();

            $table->index(['user_id', 'status'], 'idx_uploads_user_status');
            $table->index(['status', 'expires_at'], 'idx_uploads_cleanup');
        });

        // 5) Idempotensi request
        Schema::create('idempotency_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('idem_key', 80);
            $table->string('endpoint', 80);
            $table->char('request_hash', 64);
            $table->enum('status', ['processing', 'completed'])->default('processing');
            $table->unsignedSmallInteger('response_status')->nullable();
            $table->json('response_body')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->dateTime('expires_at');

            $table->unique(['user_id', 'endpoint', 'idem_key'], 'uq_idem');
            $table->index('expires_at', 'idx_idem_expires');
        });

        // 6) Event rekomendasi dari antrean offline Flutter: dedup + waktu klien
        Schema::table('user_events', function (Blueprint $table) {
            if (!Schema::hasColumn('user_events', 'client_event_id')) {
                $table->char('client_event_id', 36)->nullable()->after('rec_request_id');
            }
            if (!Schema::hasColumn('user_events', 'client_ts')) {
                $table->dateTime('client_ts')->nullable()->after('client_event_id');
            }
            try {
                $table->unique(['user_id', 'client_event_id'], 'uq_events_client');
            } catch (\Throwable $e) {
            }
        });

        // 7) Konfigurasi aplikasi mobile di app_settings
        DB::table('app_settings')->insertOrIgnore([
            ['setting_key' => 'min_supported_version_android', 'setting_value' => '1.0.0', 'description' => 'Versi minimum Android; di bawah ini API membalas 426'],
            ['setting_key' => 'min_supported_version_ios', 'setting_value' => '1.0.0', 'description' => 'Versi minimum iOS'],
            ['setting_key' => 'latest_version_android', 'setting_value' => '1.0.0', 'description' => 'Versi terbaru Android (untuk ajakan update)'],
            ['setting_key' => 'latest_version_ios', 'setting_value' => '1.0.0', 'description' => 'Versi terbaru iOS'],
            ['setting_key' => 'maintenance_mode', 'setting_value' => '0', 'description' => '1 = API membalas 503 (kecuali /health dan /app/config)'],
            ['setting_key' => 'maintenance_message', 'setting_value' => '', 'description' => 'Pesan yang ditampilkan saat pemeliharaan'],
            ['setting_key' => 'refresh_grace_seconds', 'setting_value' => '20', 'description' => 'Jendela toleransi refresh token paralel'],
            ['setting_key' => 'push_quiet_hours', 'setting_value' => '22:00-07:00', 'description' => 'Jam tenang push (WIB), kecuali kategori order'],
            ['setting_key' => 'push_recommendation_max_per_day', 'setting_value' => '1', 'description' => 'Maksimal push rekomendasi per user per hari'],
        ]);

        if (DB::getDriverName() === 'mysql') {
            try {
                DB::statement('ALTER TABLE uploads ADD CONSTRAINT chk_uploads_size CHECK (declared_size > 0 AND declared_size <= 5242880)');
            } catch (\Throwable $e) {
            }

            try {
                DB::statement('CREATE EVENT IF NOT EXISTS ev_purge_idempotency ON SCHEDULE EVERY 1 HOUR DO DELETE FROM idempotency_keys WHERE expires_at < UTC_TIMESTAMP() LIMIT 50000');
                DB::statement('CREATE EVENT IF NOT EXISTS ev_purge_push_outbox ON SCHEDULE EVERY 1 DAY DO DELETE FROM push_outbox WHERE created_at < UTC_TIMESTAMP() - INTERVAL 14 DAY LIMIT 50000');
            } catch (\Throwable $e) {
            }
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('DROP EVENT IF EXISTS ev_purge_idempotency');
            DB::statement('DROP EVENT IF EXISTS ev_purge_push_outbox');
        }

        Schema::dropIfExists('idempotency_keys');
        Schema::dropIfExists('uploads');
        Schema::dropIfExists('push_outbox');
        Schema::dropIfExists('notification_preferences');
        Schema::dropIfExists('user_devices');

        Schema::table('user_events', function (Blueprint $table) {
            if (Schema::hasColumn('user_events', 'client_event_id')) {
                $table->dropUnique('uq_events_client');
                $table->dropColumn(['client_event_id', 'client_ts']);
            }
        });

        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'category')) {
                $table->dropColumn(['category', 'deep_link']);
            }
        });

        Schema::table('refresh_tokens', function (Blueprint $table) {
            if (Schema::hasColumn('refresh_tokens', 'client_platform')) {
                $table->dropIndex('idx_refresh_device');
                $table->dropColumn(['client_platform', 'device_id', 'device_name', 'app_version', 'last_used_at']);
            }
        });

        DB::table('app_settings')->whereIn('setting_key', [
            'min_supported_version_android', 'min_supported_version_ios',
            'latest_version_android', 'latest_version_ios',
            'maintenance_mode', 'maintenance_message',
            'refresh_grace_seconds', 'push_quiet_hours', 'push_recommendation_max_per_day'
        ])->delete();
    }
};

