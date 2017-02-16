<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_incomes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('activity_id')->unsigned()->index()->nullable();
            $table->double('amount')->nullable();
            $table->bigInteger('date_added')->nullable();
            $table->string('payer')->nullable();
            $table->integer('payer_id')->unsigned()->index()->nullable();
            $table->string('supporting_doc')->nullable();
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
        Schema::dropIfExists('activity_incomes');
    }
}
