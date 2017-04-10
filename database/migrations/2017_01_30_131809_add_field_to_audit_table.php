<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToAuditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audit_trail', function($table) {
            $table->string('module_name')->nullable();
            $table->Integer('user_id')->nullable();
            $table->string('action')->nullable();
            $table->bigInteger('action_date')->nullable();
            $table->string('notes')->nullable();
            $table->Integer('reference_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audit_trail', function($table) {
            $table->dropColumn('module_name');
            $table->dropColumn('user_id');
            $table->dropColumn('action');
            $table->dropColumn('action_date');
            $table->dropColumn('notes');
            $table->dropColumn('reference_id');
        });
    }
}
