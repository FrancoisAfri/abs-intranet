<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_levels', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('level')->nullable();
            $table->string('name')->nullable();
            $table->string('plural_name')->nullable();
            $table->smallInteger('active')->nullable();
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
        Schema::dropIfExists('stock_levels');
    }
}
