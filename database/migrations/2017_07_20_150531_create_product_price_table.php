<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_price', function (Blueprint $table) {
            $table->increments('id');
            $table->double('price')->nullable();
            $table->bigInteger('start_date')->nullable();
            $table->bigInteger('end_date')->nullable();
            $table->integer('product_product_id')->index()->nullable();
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
        Schema::dropIfExists('product_price');
    }
}
