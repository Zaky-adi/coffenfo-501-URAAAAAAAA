<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Menyiapkan tabel akun Coffenfo:
//  - membuat tabel "users" dan "sessions" kalau belum ada (aman kalau sudah ada),
//  - menambah kolom profil: bean, roast, role, photo.
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();   // berisi email ATAU username
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
        }

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'bean'))  { $table->string('bean', 30)->default('Semua'); }
            if (! Schema::hasColumn('users', 'roast')) { $table->string('roast', 30)->default('Medium'); }
            if (! Schema::hasColumn('users', 'role'))  { $table->string('role', 20)->default('user'); }
            if (! Schema::hasColumn('users', 'photo')) { $table->mediumText('photo')->nullable(); }
        });

        if (! Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach (['bean', 'roast', 'role', 'photo'] as $col) {
                if (Schema::hasColumn('users', $col)) { $table->dropColumn($col); }
            }
        });
    }
};