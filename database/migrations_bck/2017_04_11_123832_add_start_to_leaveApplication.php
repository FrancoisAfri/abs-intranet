<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStartToLeaveApplication extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('leave_application', function($table) {
            $table->bigInteger('start_date')->nullable();
            $table->bigInteger('end_date')->nullable();
            $table->bigInteger('start_time')->nullable();
            $table->bigInteger('end_time')->nullable();
            $table->integer('status')->nullable();
            $table->integer('hr_id')->nullable();
            $table->integer('leave_type_id')->nullable();
            $table->integer('manager_id')->nullable();
            $table->string('reject_reason')->nullable();
            $table->integer('leave_days')->nullable();
            $table->integer('leave_hours')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('leave_application', function($table) {
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->dropColumn('status');
            $table->dropColumn('hr_id');
            $table->dropColumn('leave_type_id');
            $table->dropColumn('start_time');
            $table->dropColumn('end_time');
            $table->dropColumn('manager_id');
            $table->dropColumn('reject_reason');
            $table->dropColumn('leave_days');
            $table->dropColumn('leave_hours');

        });
    }
}
