<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->enum('status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_method', ['credit_card', 'debit_card', 'paypal', 'bank_transfer', 'cash_on_delivery', 'mobile_money'])->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->json('shipping_address');
            $table->json('billing_address')->nullable();
            $table->text('notes')->nullable();
            $table->date('estimated_delivery')->nullable();
            $table->date('actual_delivery')->nullable();
            $table->string('tracking_number')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_orders');
    }
};