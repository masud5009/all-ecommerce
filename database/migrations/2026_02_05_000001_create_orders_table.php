<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('orders')) {
            return;
        }

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->nullable();
            $table->string('billing_name')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('billing_phone')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('gateway')->nullable();
            $table->decimal('cart_total', 8, 2)->nullable();
            $table->decimal('pay_amount', 10, 0)->nullable();
            $table->decimal('discount_amount', 8, 2)->nullable();
            $table->decimal('tax', 8, 2)->nullable();
            $table->decimal('shipping_charge', 10, 0)->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('currency_symbol')->nullable();
            $table->string('currency_symbol_position')->nullable();
            $table->string('currency_text')->nullable();
            $table->string('currency_text_position')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('order_status')->nullable();
            $table->string('receipt')->nullable();
            $table->date('delivery_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
