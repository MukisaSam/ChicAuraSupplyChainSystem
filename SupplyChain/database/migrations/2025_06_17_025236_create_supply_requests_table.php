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
        Schema::create('supply_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->dateTime('due_date');
            $table->enum('status', ['pending', 'approved', 'rejected', 'in_progress', 'completed'])->default('pending');
            $table->enum('payment_type', ['cash', 'credit', 'bank_transfer'])->default('cash');
            $table->enum('delivery_method', ['pickup', 'delivery'])->default('delivery');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supply_requests');
    }
};
