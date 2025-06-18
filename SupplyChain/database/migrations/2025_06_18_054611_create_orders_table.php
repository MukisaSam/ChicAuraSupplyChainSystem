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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wholesaler_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->enum('status', ['pending', 'confirmed', 'in_production', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->dateTime('order_date');
            $table->decimal('total_amount', 12, 2);
            $table->enum('payment_method', ['cash', 'credit', 'bank_transfer', 'installment'])->default('cash');
            $table->text('shipping_address');
            $table->text('notes')->nullable();
            $table->dateTime('estimated_delivery')->nullable();
            $table->dateTime('actual_delivery')->nullable();
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
        Schema::dropIfExists('orders');
    }
};
