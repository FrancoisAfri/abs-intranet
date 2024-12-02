<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTimeAndAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees_time_and_attendance', function (Blueprint $table) {
			$table->increments('id');
			$table->uuid('uuid')->index();
            $table->integer('hours_worked')->nullable();
            $table->integer('hr_id')->nullable();
            $table->bigInteger('date_of_action')->nullable();
            $table->string('clokin_time')->nullable();
            $table->string('clockout_time')->nullable();
            $table->string('employee_number')->nullable();
            $table->string('late_arrival')->nullable();
            $table->string('early_clockout')->nullable();
            $table->string('absent')->nullable();
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
        Schema::drop('employees_time_and_attendance');
    }
}
