<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['areas', 'bean_types', 'facilities', 'menu_categories'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName): void {
                if (!Schema::hasColumn($tableName, 'is_active')) {
                    $table->boolean('is_active')->default(true);
                }
                if (!Schema::hasColumn($tableName, 'sort_order')) {
                    $table->unsignedInteger('sort_order')->default(0);
                }
            });
        }
    }

    public function down(): void
    {
        foreach (['areas', 'bean_types', 'facilities', 'menu_categories'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName): void {
                $columns = ['is_active'];
                $columns[] = 'sort_order';
                $columns = array_values(array_filter($columns, static fn (string $column): bool => Schema::hasColumn($tableName, $column)));
                if ($columns !== []) {
                    $table->dropColumn($columns);
                }
            });
        }
    }
};
