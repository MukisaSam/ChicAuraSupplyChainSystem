<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->text('address')->nullable();
            $table->enum('age_group', ['18-25', '26-35', '36-45', '46-55', '56-65', '65+'])->nullable();
            $table->enum('gender', ['male', 'female', 'other', 'prefer-not-to-say'])->nullable();
            $table->enum('income_bracket', ['low', 'middle-low', 'middle', 'middle-high', 'high', 'prefer-not-to-say'])->nullable();
            $table->json('shopping_preferences')->nullable(); // Array of preferences
            $table->enum('purchase_frequency', ['weekly', 'monthly', 'quarterly', 'yearly', 'occasional'])->nullable();
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
};