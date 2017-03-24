<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppraisalClockinResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	 // 1 for late, 2 for not late
    public function up()
    {
        Schema::create('appraisal_clockin_results', function (Blueprint $table) {
            $table->increments('id');
			 $table->integer('hr_id')->index()->unsigned()->nullable();
			 $table->integer('attendance')->index()->unsigned()->nullable();
			 $table->bigInteger('date_uploaded')->index()->unsigned()->nullable();
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
        Schema::dropIfExists('appraisal_clockin_results');
    }
}
