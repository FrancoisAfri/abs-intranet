<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEducatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('educators', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index()->nullable();
            $table->smallInteger('type')->nullable();
            $table->string('first_name')->nullable();
            $table->string('surname')->nullable();
            $table->string('id_number')->nullable();
            $table->integer('school_id')->unsigned()->index()->nullable();
            $table->integer('ethnicity')->nullable();
            $table->smallInteger('gender')->nullable();
            $table->string('cell_number')->nullable();
            $table->integer('activity_id')->unsigned()->index()->nullable();
            $table->integer('project_id')->unsigned()->index()->nullable();
            $table->string('highest_qualification')->nullable();
            $table->string('physical_address')->nullable();
            $table->string('postal_address')->nullable();
            $table->string('next_of_keen')->nullable();
            $table->string('nok_first_name')->nullable();
            $table->string('nok_surname')->nullable();
            $table->string('nok_relationship')->nullable();
            $table->string('nok_cell_number')->nullable();
            $table->string('nok_email')->nullable();
            $table->string('course_sponsored')->nullable();
            $table->string('email')->nullable();
            $table->string('institution')->nullable();
            $table->bigInteger('engagement_date')->nullable();
            $table->string('cv_doc')->nullable();
            $table->string('contract_doc')->nullable();
            $table->string('result')->nullable();
            $table->string('result_doc')->nullable();
            $table->smallInteger('first_time')->nullable();
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
        Schema::dropIfExists('educators');
    }
}
