<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppraisalsKpiRangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appraisals_kpi_ranges', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('range_from')->nullable();
			$table->integer('range_to')->nullable();
			$table->integer('kpi_id')->nullable();
			$table->double('percentage')->nullable();
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
        Schema::dropIfExists('appraisals_kpi_ranges');
    }
}
