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
        Schema::create('price_negotiations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supply_request_id')->constrained()->onDelete('cascade');
            $table->decimal('proposed_price', 10, 2);
            $table->decimal('counter_price', 10, 2)->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'counter_offered'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_negotiations');
    }
};
