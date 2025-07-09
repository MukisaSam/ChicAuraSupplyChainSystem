<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('quality_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained('work_orders')->onDelete('cascade');
            $table->string('stage');
            $table->enum('result', ['Pass', 'Fail', 'Rework'])->default('Pass');
            $table->foreignId('checked_by')->constrained('workforces')->onDelete('cascade');
            $table->dateTime('checked_at');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quality_checks');
    }
}; 