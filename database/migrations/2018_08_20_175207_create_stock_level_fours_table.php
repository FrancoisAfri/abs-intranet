<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockLevelFoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_level_fours', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name')->nullable();
            $table->smallInteger('active')->nullable();
            $table->unsignedInteger('manager_id')->index()->nullable();
            $table->unsignedInteger('parent_id')->index()->nullable();
            $table->unsignedInteger('division_level_id')->index()->nullable();
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
        Schema::dropIfExists('stock_level_fours');
    }
}
