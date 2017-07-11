<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_questions', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('division_level_1')->unsigned()->index()->nullable();
			$table->integer('division_level_2')->unsigned()->index()->nullable();
			$table->integer('division_level_3')->unsigned()->index()->nullable();
			$table->integer('division_level_4')->unsigned()->index()->nullable();
			$table->integer('division_level_5')->unsigned()->index()->nullable();
			$table->smallInteger('status')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('survey_questions');
    }
}
