<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleFireExtinguishers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('fire_extinguishers', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('date_purchased')->nullable();
            $table->integer('vehicle_id')->unsigned()->index()->nullable();
            $table->integer('supplier_id')->unsigned()->index()->nullable();
            $table->string('bar_code')->nullable();
            $table->Integer('item_no')->nullable();
            $table->string('Description')->nullable();
            $table->Integer('Weight')->nullable();
            $table->string('Serial_number')->nullable();
            $table->string('invoice_number')->nullable();
            $table->Integer('purchase_order')->nullable();
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
        Schema::dropIfExists('fire_extinguishers');
    }
}
