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
			$table->integer('stock_level_5')->unsigned()->index()->nullable();
            $table->integer('stock_level_4')->unsigned()->index()->nullable();
            $table->integer('stock_level_3')->unsigned()->index()->nullable();
            $table->integer('stock_level_2')->unsigned()->index()->nullable();
            $table->integer('stock_level_1')->unsigned()->index()->nullable();
            $table->integer('product_id')->unsigned()->index()->nullable();
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
