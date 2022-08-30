<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcurementHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procurement_history', function (Blueprint $table) {
            $table->increments('id');
            $table->String('action')->nullable();       
            $table->unsignedInteger('procurement_id')->index()->nullable();
            $table->unsignedInteger('user_id')->index()->nullable();
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
         Schema::dropIfExists('procurement_history');
    }
}