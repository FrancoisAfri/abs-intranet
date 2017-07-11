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
          Schema::table('survey_questions', function($table) {
             $table->dropColumn('division_level_1');
             $table->dropColumn('division_level_2');
             $table->dropColumn('division_level_3');
             $table->dropColumn('division_level_4');
             $table->dropColumn('division_level_5');
          });
      }

      public function down()
      {
          Schema::table('survey_questions', function($table) {
             $table->integer('division_level_1')->unsigned()->index()->nullable();
             $table->integer('division_level_2')->unsigned()->index()->nullable();
             $table->integer('division_level_3')->unsigned()->index()->nullable();
             $table->integer('division_level_4')->unsigned()->index()->nullable();
             $table->integer('division_level_5')->unsigned()->index()->nullable();
          });
      }
}
