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
        Schema::create('pending_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('user');
            $table->dateTime('visitDate')->nullable();
            $table->text('business_address')->nullable();
            $table->string('phone')->nullable();
            $table->string('license_document')->nullable();
            $table->string('document_path')->nullable();
            $table->string('business_type')->nullable();
            $table->json('preferred_categories')->nullable();
            $table->json('specialization')->nullable();
            $table->json('materials_supplied')->nullable();
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
        Schema::dropIfExists('pending_users');
    }
};
