<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppraisalsKpiNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appraisals_kpi_numbers', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('min_number')->nullable();
			$table->integer('max_number')->nullable();
			$table->integer('kpi_id')->nullable();
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
        Schema::dropIfExists('appraisals_kpi_numbers');
    }
}
