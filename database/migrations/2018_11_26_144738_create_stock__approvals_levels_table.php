<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockApprovalsLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_approvals_levels', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('division_level_5')->unsigned()->index()->nullable();
            $table->integer('division_level_4')->unsigned()->index()->nullable();
            $table->integer('division_level_3')->unsigned()->index()->nullable();
            $table->integer('division_level_2')->unsigned()->index()->nullable();
            $table->integer('division_level_1')->unsigned()->index()->nullable();
            $table->integer('employee_id')->unsigned()->index()->nullable();
			$table->string('step_name')->unsigned()->index()->nullable();
			$table->integer('step_number')->unsigned()->index()->nullable();
			$table->double('max_amount')->unsigned()->index()->nullable();
			$table->integer('job_title')->unsigned()->index()->nullable();
			$table->smallInteger('status')->nullable();
			$table->bigInteger('date_added')->nullable();
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
        Schema::dropIfExists('stock_approvals_levels');
    }
}
