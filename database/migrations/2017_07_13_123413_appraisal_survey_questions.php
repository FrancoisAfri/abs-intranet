<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AppraisalSurveyQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appraisal_survey_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('survey_id')->unsigned()->index()->nullable();
            $table->integer('question_id')->unsigned()->index()->nullable();
            $table->smallInteger('result')->nullable();
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
        Schema::dropIfExists('appraisal_survey_questions');
    }
}
