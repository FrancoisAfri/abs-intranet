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
            $table->unsignedInteger('product_id')->index()->nullable();
            $table->unsignedInteger('category_id')->index()->nullable();
            $table->unsignedInteger('user_id')->index()->nullable();
            $table->unsignedInteger('vehicle_id')->index()->nullable();
            $table->unsignedInteger('avalaible_stock')->index()->nullable();
            $table->unsignedBigInteger('action_date')->index()->nullable();
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

