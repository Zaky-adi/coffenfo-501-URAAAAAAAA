<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('points'); // positif = dapat, negatif = ditukar
            $table->string('type')->index(); // review_bonus, redeem, adjustment
            $table->string('reference_type')->nullable(); // morph: Review, VoucherRedemption, dll
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->unsignedInteger('balance_after')->default(0); // saldo setelah transaksi (audit)
            $table->string('note')->nullable();
            $table->timestamps();

            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_transactions');
    }
};
