<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToLeaveConfigurationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_configuration', function ($table) {
            $table->smallInteger('allow_unpaid_leave_when_annual_done')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leave_configuration', function ($table) {
            $table->dropColumn('allow_unpaid_leave_when_annual_done');
        });
    }
}
