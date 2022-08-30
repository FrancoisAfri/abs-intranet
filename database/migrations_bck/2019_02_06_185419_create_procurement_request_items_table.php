<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcurementRequestItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procurement_request_items', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('procurement_id')->nullable();
			$table->integer('product_id')->nullable();
			$table->integer('category_id')->nullable();
			$table->integer('quantity')->nullable();
			$table->string('item_name')->nullable();
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
        Schema::dropIfExists('procurement_request_items');
    }
}
