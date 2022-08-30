<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductPromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_promotions', function (Blueprint $table) {
            $table->increments('id');
            $table->String('name')->nullable();
            $table->String('description')->nullable();
            $table->bigInteger('start_date')->nullable();
            $table->bigInteger('end_date')->nullable();
            $table->double('discount')->nullable();
            $table->double('price')->nullable();
            $table->smallInteger('status')->nullable();
            $table->integer('product_product_id')->unsigned()->index()->nullable();
            $table->integer('product_packages_id')->unsigned()->index()->nullable();
            $table->integer('category_id')->nullable();
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
        Schema::dropIfExists('product_promotions');
    }
}
