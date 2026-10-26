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
        // Alter tabel users untuk memenuhi spesifikasi Modul 02 di DATABASE.md
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'public_id')) {
                $table->char('public_id', 36)->nullable()->after('id');
            }
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username', 40)->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'suspended', 'deleted'])->default('active')->after('role');
            }
            if (!Schema::hasColumn('users', 'failed_login_count')) {
                $table->unsignedTinyInteger('failed_login_count')->default(0)->after('email_verified_at');
            }
            if (!Schema::hasColumn('users', 'locked_until')) {
                $table->dateTime('locked_until')->nullable()->after('failed_login_count');
            }
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->dateTime('last_login_at')->nullable()->after('locked_until');
            }
            if (!Schema::hasColumn('users', 'password_changed_at')) {
                $table->dateTime('password_changed_at')->nullable()->after('last_login_at');
            }
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // Backfill UUID untuk user yang ada
        $users = DB::table('users')->whereNull('public_id')->get();
        foreach ($users as $u) {
            DB::table('users')->where('id', $u->id)->update([
                'public_id' => Str::uuid()->toString(),
            ]);
        }

        Schema::table('users', function (Blueprint $table) {
            try {
                $table->char('public_id', 36)->nullable(false)->unique('uq_users_public_id')->change();
            } catch (\Throwable $e) {
            }
            try {
                $table->unique('username', 'uq_users_username');
            } catch (\Throwable $e) {
            }
            try {
                $table->index(['role', 'status'], 'idx_users_role_status');
            } catch (\Throwable $e) {
            }
        });

        Schema::create('user_preferences', function (Blueprint $table) {
            $table->foreignId('user_id')->primary()->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('bean_type_id')->nullable();
            $table->unsignedTinyInteger('roast_level_id')->nullable();
            $table->index('bean_type_id');
            $table->index('roast_level_id');
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('user_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['password_reset', 'email_verify']);
            $table->char('token_hash', 64)->unique();
            $table->dateTime('expires_at');
            $table->dateTime('used_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['user_id', 'type']);
            $table->index('expires_at');
        });

        Schema::create('refresh_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->char('family_id', 36);
            $table->char('token_hash', 64)->unique();
            $table->string('user_agent', 255)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->dateTime('expires_at');
            $table->dateTime('revoked_at')->nullable();
            $table->unsignedBigInteger('replaced_by_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['user_id', 'revoked_at']);
            $table->index('family_id');
            $table->index('expires_at');
        });

        Schema::create('login_attempts', function (Blueprint $table) {
            $table->id();
            $table->char('identifier_hash', 64);
            $table->string('ip_address', 45);
            $table->boolean('success');
            $table->string('user_agent', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['identifier_hash', 'created_at']);
            $table->index(['ip_address', 'created_at']);
        });

        if (DB::getDriverName() === 'mysql') {
            try {
                DB::statement("CREATE EVENT IF NOT EXISTS ev_purge_login_attempts ON SCHEDULE EVERY 1 DAY DO DELETE FROM login_attempts WHERE created_at < UTC_TIMESTAMP() - INTERVAL 30 DAY");
                DB::statement("CREATE EVENT IF NOT EXISTS ev_purge_expired_tokens ON SCHEDULE EVERY 1 DAY DO BEGIN DELETE FROM user_tokens WHERE expires_at < UTC_TIMESTAMP() - INTERVAL 7 DAY; DELETE FROM refresh_tokens WHERE expires_at < UTC_TIMESTAMP() - INTERVAL 7 DAY; END");
            } catch (\Throwable $e) {
            }
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('DROP EVENT IF EXISTS ev_purge_login_attempts');
            DB::statement('DROP EVENT IF EXISTS ev_purge_expired_tokens');
        }
        Schema::dropIfExists('login_attempts');
        Schema::dropIfExists('refresh_tokens');
        Schema::dropIfExists('user_tokens');
        Schema::dropIfExists('user_preferences');

        Schema::table('users', function (Blueprint $table) {
            try {
                $table->dropIndex('idx_users_role_status');
                $table->dropUnique('uq_users_username');
                $table->dropUnique('uq_users_public_id');
            } catch (\Throwable $e) {
            }
            $cols = ['public_id', 'username', 'status', 'failed_login_count', 'locked_until', 'last_login_at', 'password_changed_at', 'deleted_at'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
