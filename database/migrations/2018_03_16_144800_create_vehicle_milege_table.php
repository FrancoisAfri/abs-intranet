<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleMilegeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    Schema::create('vehicle_mileage', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('date_created')->nullable();
            $table->bigInteger('date_taken')->nullable();
			$table->integer('vehicle_id')->index()->unsigned()->nullable();
            $table->integer('odometer_reading')->nullable();
            $table->smallInteger('type')->nullable();
            $table->integer('booking_id')->index()->unsigned()->nullable();
            $table->string('hours_reading')->nullable();
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
        Schema::dropIfExists('vehicle_mileage');
    }
}