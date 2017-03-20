<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppraisalKPIIntRangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appraisal_k_p_i_int_ranges', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('score')->nullable();
            $table->double('percentage')->nullable();
            $table->integer('kpi_id')->index()->unsigned()->nullable();
            $table->smallInteger('status')->nullable();
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
        Schema::dropIfExists('appraisal_k_p_i_int_ranges');
    }
}
