<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_stocks', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('employee_id')->nullable();
			$table->integer('on_behalf_of')->nullable();
			$table->integer('on_behalf_employee_id')->nullable();
			$table->bigInteger('date_created')->nullable();
            $table->bigInteger('date_approved')->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('request_stocks');
    }
}
