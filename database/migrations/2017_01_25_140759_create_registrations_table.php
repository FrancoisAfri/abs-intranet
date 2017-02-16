<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('registration_type')->nullable();
            $table->integer('programme_id')->unsigned()->index()->nullable();
            $table->integer('project_id')->unsigned()->index()->nullable();
            $table->integer('learner_id')->unsigned()->index()->nullable();
            $table->integer('educator_id')->unsigned()->index()->nullable();
            $table->integer('gen_public_id')->unsigned()->index()->nullable();
            $table->integer('registration_year')->nullable();
            $table->smallInteger('course_type')->nullable();
            $table->smallInteger('registration_semester')->nullable();
            $table->double('gen_pub_reg_fee')->nullable();
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
        Schema::dropIfExists('registrations');
    }
}
