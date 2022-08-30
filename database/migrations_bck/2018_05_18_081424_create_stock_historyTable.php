<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
     Schema::create('stock_history', function (Blueprint $table) {
            $table->increments('id');
            $table->String('action')->nullable();       
            $table->integer('product_id')->unsigned()->index()->nullable();
            $table->integer('category_id')->unsigned()->index()->nullable();
            $table->integer('user_id')->unsigned()->index()->nullable();
            $table->integer('vehicle_id')->unsigned()->index()->nullable();
            $table->integer('avalaible_stock')->unsigned()->index()->nullable();
            $table->bigInteger('action_date')->unsigned()->index()->nullable();
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
        Schema::dropIfExists('stock_history');
    }

}

