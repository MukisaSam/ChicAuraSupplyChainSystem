<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('audit_logs', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('audit_logs', 'action')) {
                $table->string('action')->after('user_id');
            }
            if (!Schema::hasColumn('audit_logs', 'details')) {
                $table->text('details')->nullable()->after('action');
            }
            if (!Schema::hasColumn('audit_logs', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }
            if (!Schema::hasColumn('audit_logs', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            if (Schema::hasColumn('audit_logs', 'user_id')) {
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('audit_logs', 'action')) {
                $table->dropColumn('action');
            }
            if (Schema::hasColumn('audit_logs', 'details')) {
                $table->dropColumn('details');
            }
            if (Schema::hasColumn('audit_logs', 'created_at')) {
                $table->dropColumn('created_at');
            }
            if (Schema::hasColumn('audit_logs', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
        });
    }
};
