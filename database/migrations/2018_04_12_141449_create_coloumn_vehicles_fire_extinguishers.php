<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColoumnVehiclesFireExtinguishers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
     Schema::create('vehicle_fire_extinguisher', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('date_purchased')->nullable();
            $table->integer('vehicle_id')->unsigned()->index()->nullable();
            $table->integer('supplier_id')->unsigned()->index()->nullable();
            $table->string('bar_code')->nullable();
            $table->string('item_no')->nullable();
            $table->string('Description')->nullable();
            $table->double('Weight')->nullable();
            $table->string('Serial_number')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('purchase_order')->nullable();
            $table->double('Cost')->nullable();
            $table->double('rental_amount')->nullable();
            $table->string('image')->nullable();
            $table->Integer('Status')->nullable();
            $table->string('notes')->nullable();
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
        Schema::dropIfExists('vehicle_fire_extinguisher');
    }
}
