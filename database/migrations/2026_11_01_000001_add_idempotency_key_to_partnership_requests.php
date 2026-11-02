<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partnership_requests', function (Blueprint $table): void {
            if (!Schema::hasColumn('partnership_requests', 'public_id')) {
                $table->char('public_id', 36)->nullable();
            }
            if (!Schema::hasColumn('partnership_requests', 'idempotency_key')) {
                $table->string('idempotency_key', 80)->nullable()->unique();
            }
        });
        foreach (DB::table('partnership_requests')->whereNull('public_id')->get(['id']) as $row) {
            DB::table('partnership_requests')->where('id', $row->id)->update(['public_id' => (string) Str::uuid()]);
        }
        Schema::table('partnership_requests', function (Blueprint $table): void {
            $table->char('public_id', 36)->nullable(false)->unique('uq_partnership_requests_public_id')->change();
        });
    }

    public function down(): void
    {
        Schema::table('partnership_requests', function (Blueprint $table): void {
            if (Schema::hasColumn('partnership_requests', 'idempotency_key')) {
                $table->dropUnique(['idempotency_key']);
            }
            if (Schema::hasColumn('partnership_requests', 'public_id')) $table->dropUnique('uq_partnership_requests_public_id');
            $columns = array_values(array_filter(['idempotency_key', 'public_id'], static fn (string $column): bool => Schema::hasColumn('partnership_requests', $column)));
            if ($columns !== []) $table->dropColumn($columns);
        });
    }
};
