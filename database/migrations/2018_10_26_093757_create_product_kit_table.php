<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductKitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_kit', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unique();
            $table->string('name')->nullable();
            $table->smallInteger('status')->nullable();
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
        Schema::drop('product_kit');
    }
}
