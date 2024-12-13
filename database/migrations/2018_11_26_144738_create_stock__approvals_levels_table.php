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
			$table->unsignedInteger('division_level_5')->index()->nullable();
            $table->unsignedInteger('division_level_4')->index()->nullable();
            $table->unsignedInteger('division_level_3')->index()->nullable();
            $table->unsignedInteger('division_level_2')->index()->nullable();
            $table->unsignedInteger('division_level_1')->index()->nullable();
            $table->unsignedInteger('employee_id')->index()->nullable();
			$table->string('step_name')->index()->nullable();
			$table->unsignedInteger('step_number')->index()->nullable();
			$table->double('max_amount')->index()->nullable();
			$table->unsignedInteger('job_title')->index()->nullable();
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
