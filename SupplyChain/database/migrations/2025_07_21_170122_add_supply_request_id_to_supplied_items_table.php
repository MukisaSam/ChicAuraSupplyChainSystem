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
        Schema::table('supplied_items', function (Blueprint $table) {
            $table->unsignedBigInteger('supply_request_id')->nullable()->after('id');
            $table->foreign('supply_request_id')->references('id')->on('supply_requests')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supplied_items', function (Blueprint $table) {
            //
        });
    }
};
