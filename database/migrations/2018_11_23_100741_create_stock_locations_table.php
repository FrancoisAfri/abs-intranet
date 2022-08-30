<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_locations', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('stock_level_5')->index()->nullable();
            $table->unsignedInteger('stock_level_4')->index()->nullable();
            $table->unsignedInteger('stock_level_3')->index()->nullable();
            $table->unsignedInteger('stock_level_2')->index()->nullable();
            $table->unsignedInteger('stock_level_1')->index()->nullable();
            $table->unsignedInteger('product_id')->index()->nullable();
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
        Schema::dropIfExists('stock_locations');
    }
}
