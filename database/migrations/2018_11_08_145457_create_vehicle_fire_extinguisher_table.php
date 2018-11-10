<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleFireExtinguisherTable extends Migration
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
			$table->string('attachement')->unsigned()->index()->nullable();
			$table->string('Serial_number')->unsigned()->index()->nullable();
			$table->bigInteger('date_purchased')->unsigned()->index()->nullable();
			$table->integer('vehicle_id')->unsigned()->index()->nullable();
			$table->integer('supplier_id')->unsigned()->index()->nullable();
			$table->string('bar_code')->unsigned()->index()->nullable();
			$table->string('item_no')->unsigned()->index()->nullable();
			$table->string('Description')->unsigned()->index()->nullable();
			$table->integer('Weight')->unsigned()->index()->nullable();
			$table->string('invoice_number')->unsigned()->index()->nullable();
			$table->string('purchase_order')->unsigned()->index()->nullable();
			$table->double('Cost')->unsigned()->index()->nullable();
			$table->double('rental_amount')->unsigned()->index()->nullable();
			$table->string('image')->unsigned()->index()->nullable();
			$table->integer('Status')->unsigned()->index()->nullable();
			$table->string('notes')->unsigned()->index()->nullable();
			$table->integer('capturer_id')->unsigned()->index()->nullable();
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
