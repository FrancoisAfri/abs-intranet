<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNswStxGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nsw_stx_grades', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('nsw_id')->unsigned()->index()->nullable();
            //$table->string('nsw_id')->nullable();
            $table->integer('learner_number')->nullable();
            $table->integer('male_number')->nullable();
            $table->integer('female_number')->nullable();
            $table->integer('african_number')->nullable();
            $table->integer('asian_number')->nullable();
            $table->integer('caucasian_number')->nullable();
            $table->integer('coloured_number')->nullable();
            $table->integer('indian_number')->nullable();
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
        Schema::dropIfExists('nsw_stx_grades');
    }
}
