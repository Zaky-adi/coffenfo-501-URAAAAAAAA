<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wishlists', function (Blueprint $table) {
            $table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        DB::statement('UPDATE wishlists SET updated_at = COALESCE(created_at, NOW()) WHERE updated_at IS NULL');

        Schema::table('wishlists', function (Blueprint $table) {
            $table->timestamp('created_at')->useCurrent()->change();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->change();
        });
    }

    public function down(): void
    {
        Schema::table('wishlists', function (Blueprint $table) {
            $table->dropColumn('updated_at');
            $table->timestamp('created_at')->nullable()->change();
        });
    }
};
