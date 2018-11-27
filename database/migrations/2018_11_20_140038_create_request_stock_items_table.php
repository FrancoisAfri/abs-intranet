<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestStockItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_stock_items', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('product_id')->nullable();
			$table->integer('category_id')->nullable();
			$table->integer('quantity')->nullable();
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
        Schema::dropIfExists('request_stock_items');
    }
}
