<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLearnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learners', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('type')->nullable();
            $table->string('first_name')->nullable();
            $table->string('surname')->nullable();
            $table->integer('grade')->nullable();
            $table->string('id_number')->nullable();
            $table->bigInteger('date_of_birth')->nullable();
            $table->integer('school_id')->unsigned()->index()->nullable();
            $table->integer('ethnicity')->unsigned()->index()->nullable();
            $table->smallInteger('gender')->nullable();
            $table->string('cell_number')->nullable();
            $table->string('field_of_choice')->nullable();
            $table->integer('activity_id')->unsigned()->index()->nullable();
            $table->smallInteger('first_time')->nullable();
            $table->string('physical_address')->nullable();
            $table->string('learning_condition')->nullable();
            $table->string('physical_disability')->nullable();
            $table->string('medical_condition')->nullable();
            $table->string('parent_name')->nullable();
            $table->string('parent_number')->nullable();
            $table->integer('educator_id')->unsigned()->index()->nullable();
            $table->string('toy_library', 750)->nullable();
            $table->string('attendance_reg_doc')->nullable();
            $table->string('result_doc')->nullable();
            $table->bigInteger('date_started_project')->nullable();
            $table->smallInteger('active')->nullable();
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
        Schema::dropIfExists('learners');
    }
}
