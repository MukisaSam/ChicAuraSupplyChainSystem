<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bill_of_material_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bom_id')->constrained('bill_of_materials')->onDelete('cascade');
            $table->foreignId('raw_item_id')->constrained('items')->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bill_of_material_components');
    }
}; 