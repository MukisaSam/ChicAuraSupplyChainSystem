<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_order_id')->constrained('customer_orders')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('unit_price', 8, 2);
            $table->decimal('total_price', 10, 2);
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_order_items');
    }
};