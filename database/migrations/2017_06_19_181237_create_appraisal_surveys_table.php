<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppraisalSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appraisal_surveys', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('feedback_date')->nullable();
            $table->integer('hr_person_id')->unsigned()->index()->nullable();
            $table->string('client_name')->nullable();
            $table->string('booking_number')->nullable();
            $table->smallInteger('attitude_enthusiasm')->nullable();
            $table->smallInteger('expertise')->nullable();
            $table->smallInteger('efficiency')->nullable();
            $table->smallInteger('attentive_listening')->nullable();
            $table->smallInteger('general_overall_assistance')->nullable();
            $table->string('additional_comments')->nullable();
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
        Schema::dropIfExists('appraisal_surveys');
    }
}
