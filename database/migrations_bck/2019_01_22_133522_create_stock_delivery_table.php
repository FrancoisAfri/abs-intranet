<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockDeliveryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('stock_deliveries', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('employee_id')->unsigned()->index()->nullable();
			$table->string('delivery_slip')->unsigned()->index()->nullable();
			$table->integer('request_stocks_id')->unsigned()->index()->nullable();
			$table->smallInteger('status')->nullable();
			$table->bigInteger('date_delivered')->nullable();
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
        Schema::dropIfExists('stock_deliveries');
    }
}
