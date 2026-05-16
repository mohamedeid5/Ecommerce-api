<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 20)->unique();  // ORD-2025-000001
            $table->foreignId('user_id')->constrained()->restrictOnDelete();

            $table->string('status', 30)->default('pending_payment');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping_fee', 10, 2);
            $table->decimal('tax_amount', 10, 2);
            $table->decimal('total', 10, 2);
            $table->decimal('tax_rate', 5, 4);  // 0.1400 = 14%

            $table->string('shipping_full_name');
            $table->string('shipping_phone', 20);
            $table->string('shipping_street');
            $table->string('shipping_building')->nullable();
            $table->string('shipping_apartment')->nullable();
            $table->string('shipping_city');
            $table->string('shipping_governorate');
            $table->string('shipping_postal_code', 10)->nullable();
            $table->text('shipping_notes')->nullable();

            $table->text('customer_notes')->nullable();
            $table->timestamp('placed_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index(['user_id', 'status']);
            $table->index('placed_at');
        });
    }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('orders');
        }
    };
