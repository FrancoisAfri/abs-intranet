<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocationsToEmployeesTimeAndAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees_time_and_attendance', function ($table) {
            //
            $table->string('clockin_locations')->index()->nullable();
            $table->string('clockout_locations')->index()->nullable();
            $table->integer('onleave')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees_time_and_attendance', function ($table) {
            //
            $table->dropColumn('clockin_locations');
            $table->dropColumn('clockout_locations');
            $table->dropColumn('onleave');
        });
    }
}
