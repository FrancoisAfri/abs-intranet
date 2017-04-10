<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppraisalsKpisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appraisals_kpis', function (Blueprint $table) {
            $table->increments('id');
			$table->string('measurement')->nullable();
			$table->string('source_of_evidence')->nullable();
			$table->string('indicator')->nullable();
            $table->smallInteger('status')->nullable();
            $table->smallInteger('kpi_type')->nullable();
            $table->integer('template_id')->nullable();
            $table->integer('kpa_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('weight')->nullable();
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
        Schema::dropIfExists('appraisals_kpis');
    }
}
