<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppraisalKPIResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appraisal_k_p_i_results', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hr_id')->index()->unsigned()->nullable();
            $table->integer('kip_id')->index()->unsigned()->nullable();
            $table->integer('template_id')->index()->unsigned()->nullable();
            $table->double('result')->nullable();
            $table->integer('date_uploaded')->nullable();
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('appraisal_k_p_i_results');
    }
}