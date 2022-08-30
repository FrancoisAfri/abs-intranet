<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobCardInstructions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_card_instructions', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('job_card_id')->index()->nullable();
            $table->string('instruction_details')->nullable();
            $table->integer('status')->nullable();
            $table->bigInteger('completion_date')->nullable();
			$table->string('completion_time')->index()->nullable();
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
        Schema::dropIfExists('job_card_instructions');
    }
}
