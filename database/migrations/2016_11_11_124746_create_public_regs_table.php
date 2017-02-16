<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicRegsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_regs', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('type')->nullable();
            $table->string('names')->nullable();
            $table->string('first_name')->nullable();
            $table->string('surname')->nullable();
            $table->string('id_number')->nullable();
            $table->integer('ethnicity')->unsigned()->index()->nullable();
            $table->integer('activity_id')->unsigned()->index()->nullable();
            $table->smallInteger('gender')->nullable();
            $table->string('cell_number')->nullable();
            $table->string('phys_address')->nullable();
            $table->string('postal_address')->nullable();
            $table->string('highest_qualification')->nullable();
            $table->string('previous_computer_exp')->nullable();
            $table->string('programme_discovery')->nullable();
            $table->string('completed_certificates')->nullable();
            $table->bigInteger('date')->nullable();
            $table->string('attendance_doc')->nullable();
            $table->string('result')->nullable();
            $table->double('registration_fee')->nullable();
            $table->string('employment_status')->nullable();
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
        Schema::dropIfExists('public_regs');
    }
}
