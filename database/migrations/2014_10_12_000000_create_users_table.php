<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('user')->index(); // user, owner, admin
            $table->string('phone', 20)->nullable();
            $table->string('photo')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();   // lokasi user (untuk kafe nearby)
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedInteger('points_balance')->default(0); // cache saldo poin
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
