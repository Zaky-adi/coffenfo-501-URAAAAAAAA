<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique('uq_cart_user')->constrained('users')->cascadeOnDelete();
            $table->foreignId('cafe_id')->constrained('cafes')->cascadeOnDelete();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained('carts')->cascadeOnDelete();
            $table->foreignId('menu_item_id')->constrained('menu_items')->cascadeOnDelete();
            $table->unsignedTinyInteger('quantity')->default(1);
            $table->string('note', 200)->nullable();

            $table->unique(['cart_id', 'menu_item_id'], 'uq_cart_item');
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->char('public_id', 36)->unique('uq_orders_public_id');
            $table->string('order_code', 20)->unique('uq_orders_code');
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('cafe_id')->constrained('cafes')->restrictOnDelete();
            $table->enum('order_type', ['dine_in', 'takeaway']);
            $table->string('table_number', 10)->nullable();
            $table->string('customer_note', 300)->nullable();
            $table->enum('status', ['pending_payment', 'waiting_confirmation', 'processing', 'ready', 'completed', 'cancelled'])->default('pending_payment');
            $table->unsignedInteger('subtotal');
            $table->unsignedInteger('service_fee')->default(0);
            $table->unsignedInteger('discount_amount')->default(0);
            $table->unsignedInteger('total_amount');
            $table->foreignId('voucher_redemption_id')->nullable()->constrained('voucher_redemptions')->nullOnDelete();
            $table->unsignedTinyInteger('est_prep_min')->nullable();
            $table->unsignedTinyInteger('est_prep_max')->nullable();
            $table->string('cancel_reason', 255)->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->dateTime('accepted_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();

            $table->index(['cafe_id', 'status', 'created_at'], 'idx_orders_cafe_status');
            $table->index(['user_id', 'created_at'], 'idx_orders_user');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('menu_item_id')->nullable()->constrained('menu_items')->nullOnDelete();
            $table->string('item_name', 150);
            $table->unsignedInteger('unit_price');
            $table->unsignedTinyInteger('quantity');
            $table->string('note', 200)->nullable();
            $table->unsignedInteger('line_total');

            $table->index('order_id', 'idx_order_items_order');
        });

        Schema::create('order_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('from_status', 30)->nullable();
            $table->string('to_status', 30);
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('note', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['order_id', 'created_at'], 'idx_osh_order');
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->char('public_id', 36)->unique('uq_payments_public_id');
            $table->foreignId('order_id')->constrained('orders')->restrictOnDelete();
            $table->enum('method', ['ewallet', 'bank_transfer']);
            $table->string('channel', 30);
            $table->string('provider', 30);
            $table->string('provider_ref', 100)->nullable();
            $table->unsignedInteger('amount');
            $table->enum('status', ['pending', 'paid', 'failed', 'expired', 'refunded'])->default('pending');
            $table->dateTime('expires_at')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->json('callback_payload')->nullable();
            $table->timestamps();

            $table->unique(['provider', 'provider_ref'], 'uq_payments_provider_ref');
            $table->index(['order_id', 'status'], 'idx_payments_order');
        });

        // Menutup relasi voucher_redemptions -> orders
        Schema::table('voucher_redemptions', function (Blueprint $table) {
            if (!Schema::hasColumn('voucher_redemptions', 'used_order_id')) {
                $table->foreignId('used_order_id')->nullable()->after('used_at')->constrained('orders')->nullOnDelete();
            }
        });

        if (DB::getDriverName() === 'mysql') {
            try {
                DB::statement('ALTER TABLE cart_items ADD CONSTRAINT chk_cart_qty CHECK (quantity BETWEEN 1 AND 99)');
                DB::statement('ALTER TABLE orders ADD CONSTRAINT chk_orders_total CHECK (total_amount + discount_amount = subtotal + service_fee)');
                DB::statement('ALTER TABLE order_items ADD CONSTRAINT chk_oi_qty CHECK (quantity BETWEEN 1 AND 99)');
                DB::statement('ALTER TABLE order_items ADD CONSTRAINT chk_oi_total CHECK (line_total = unit_price * quantity)');
            } catch (\Throwable $e) {
            }
        }
    }

    public function down(): void
    {
        Schema::table('voucher_redemptions', function (Blueprint $table) {
            if (Schema::hasColumn('voucher_redemptions', 'used_order_id')) {
                $table->dropForeign(['used_order_id']);
                $table->dropColumn('used_order_id');
            }
        });

        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_status_history');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
    }
};

