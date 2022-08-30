<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToFleetCommunicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fleet_communications', function ($table) {
            $table->unsignedInteger('employee_id')->index()->nullable();
            $table->unsignedInteger('send_type')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('fleet_communications', function ($table) {
            $table->dropColumn('employee_id');
            $table->dropColumn('send_type');
        });
    }
}
