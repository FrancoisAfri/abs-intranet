<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemovingFieldToSurveyQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
      {
          Schema::dropIfExists('survey_questions');
      }

      public function down()
      {
          /*Schema::table('survey_questions', function($table) {
             $table->integer('division_level_1')->unsigned()->index()->nullable();
             $table->integer('division_level_2')->unsigned()->index()->nullable();
             $table->integer('division_level_3')->unsigned()->index()->nullable();
             $table->integer('division_level_4')->unsigned()->index()->nullable();
             $table->integer('division_level_5')->unsigned()->index()->nullable();
          });*/
      }
}
