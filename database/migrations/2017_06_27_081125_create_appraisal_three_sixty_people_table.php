<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppraisalThreeSixtyPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appraisal_three_sixty_people', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hr_id')->unsigned()->index()->nullable();
            $table->integer('appraiser_id')->unsigned()->index()->nullable();
            $table->bigInteger('appraisal_month')->nullable();
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
        Schema::dropIfExists('appraisal_three_sixty_people');
    }
}
