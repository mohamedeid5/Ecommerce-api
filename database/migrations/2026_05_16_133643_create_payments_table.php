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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_reference', 50)->unique();  // PAY-2025-000001
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();

            $table->string('provider', 30);  // 'mock', 'paymob', 'fawry' لاحقاً
            $table->string('provider_payment_id')->nullable();  // ID من عند الـ provider

            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('EGP');

            $table->string('status', 30);  // pending, succeeded, failed, refunded
            $table->text('failure_reason')->nullable();

            $table->string('webhook_event_id')->nullable()->unique();

            $table->timestamp('initiated_at');
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            $table->index(['order_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
