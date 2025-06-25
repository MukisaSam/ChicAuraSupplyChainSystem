<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('workforces', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('manufacturer_id');
            $table->string('fullname');
            $table->string('email')->unique();
            $table->string('contact_info')->nullable();
            $table->string('address')->nullable();
            $table->string('job_role');
            $table->enum('status', ['Active', 'Inactive', 'On Leave', 'Terminated'])->default('Active');
            $table->date('hire_date')->nullable();
            $table->integer('salary')->nullable();
            $table->timestamps();

            $table->foreign('manufacturer_id')->references('id')->on('manufacturers')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('workforces');
    }
}; 