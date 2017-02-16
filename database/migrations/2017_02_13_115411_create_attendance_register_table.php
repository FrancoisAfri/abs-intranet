<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceRegisterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_register', function (Blueprint $table) {
            $table->increments('id');
			$table->smallInteger('registration_type')->nullable();
			$table->integer('programme_id')->unsigned()->index()->nullable();
            $table->integer('project_id')->unsigned()->index()->nullable();
            $table->integer('learner_id')->unsigned()->index()->nullable();
            $table->integer('educator_id')->unsigned()->index()->nullable();
            $table->integer('gen_public_id')->unsigned()->index()->nullable();
            $table->integer('attendance')->nullable();
			$table->integer('registration_year')->nullable();
			$table->bigInteger('date_attended')->nullable();
            $table->smallInteger('course_type')->nullable();
            $table->smallInteger('registration_semester')->nullable();
            
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
        Schema::dropIfExists('attendance_register');
    }
}
