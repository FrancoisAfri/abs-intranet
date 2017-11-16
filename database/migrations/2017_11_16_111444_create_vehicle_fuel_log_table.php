<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleFuelLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_fuel_log', function (Blueprint $table) {
             $table->increments('id');
             $table->integer('driver')->nullable()->unsigned()->index();
             $table->string('document_number')->nullable();
             $table->bigInteger('date')->nullable();
             $table->integer('tank_type')->nullable();
             $table->integer('tank_name')->nullable(); 
             $table->integer('litres')->nullable();
             $table->string('hours_reading')->nullable();
             $table->string('description')->nullable();
             $table->integer('captured_by')->nullable();
             $table->integer('vehicleID')->nullable();
             $table->integer('rensonsible_person')->nullable()->unsigned()->index();
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
        Schema::dropIfExists('vehicle_fuel_log');
    }
}
