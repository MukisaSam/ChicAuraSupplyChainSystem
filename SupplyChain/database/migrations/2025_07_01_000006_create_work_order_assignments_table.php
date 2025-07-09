<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('work_order_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained('work_orders')->onDelete('cascade');
            $table->foreignId('workforce_id')->constrained('workforces')->onDelete('cascade');
            $table->string('role');
            $table->dateTime('assigned_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('work_order_assignments');
    }
}; 