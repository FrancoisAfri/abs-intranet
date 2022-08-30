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
			$table->string('attachement')->index()->nullable();
			$table->string('Serial_number')->index()->nullable();
			$table->unsignedInteger('date_purchased')->index()->nullable();
			$table->unsignedInteger('vehicle_id')->index()->nullable();
			$table->unsignedInteger('supplier_id')->index()->nullable();
			$table->string('bar_code')->index()->nullable();
			$table->string('item_no')->index()->nullable();
			$table->string('Description')->index()->nullable();
			$table->unsignedInteger('Weight')->index()->nullable();
			$table->string('invoice_number')->index()->nullable();
			$table->string('purchase_order')->index()->nullable();
			$table->double('Cost')->unsigned()->index()->nullable();
			$table->double('rental_amount')->unsigned()->index()->nullable();
			$table->string('image')->index()->nullable();
			$table->unsignedInteger('Status')->index()->nullable();
			$table->string('notes')->index()->nullable();
			$table->unsignedInteger('capturer_id')->index()->nullable();
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
