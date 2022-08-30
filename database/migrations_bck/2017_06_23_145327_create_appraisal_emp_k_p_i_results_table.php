<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppraisalEmpKPIResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appraisal_emp_k_p_i_results', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hr_id')->index()->unsigned()->nullable();
            $table->integer('kpi_id')->index()->unsigned()->nullable();
            $table->integer('template_id')->index()->unsigned()->nullable();
            $table->integer('appraiser_id')->index()->unsigned()->nullable();
            $table->double('score')->nullable();
            $table->double('percent')->nullable();
            $table->bigInteger('date_uploaded')->nullable();
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
        Schema::dropIfExists('appraisal_emp_k_p_i_results');
    }
}
