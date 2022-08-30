<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFueltankTopUp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuel_tank_topUp', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('supplier_id')->unsigned()->index()->nullable();
            $table->string('document_no')->nullable();
            $table->bigInteger('document_date')->nullable();
            $table->bigInteger('topup_date')->nullable();
            $table->integer('type')->nullable();
            $table->integer('reading_before_filling')->nullable();
            $table->integer('reading_after_filling')->nullable();
            $table->integer('litres')->nullable();
            $table->double('cost_per_litre')->nullable();
            $table->double('total_cost')->nullable();
            $table->string('description')->nullable();
            $table->integer('received_by')->nullable();
            $table->integer('captured_by')->nullable();
            $table->smallInteger('status');
            $table->integer('tank_id')->unsigned()->index()->nullable();
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
        Schema::dropIfExists('fuel_tank_topUp');
    }
}
