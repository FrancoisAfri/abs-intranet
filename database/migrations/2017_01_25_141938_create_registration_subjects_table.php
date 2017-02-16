<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistrationSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registration_subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('registration_id')->unsigned()->index()->nullable();
            $table->integer('subject_id')->unsigned()->index()->nullable();
            $table->double('result')->nullable();
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
        Schema::dropIfExists('registration_subjects');
    }
}
