<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleIncidentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_incidents', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('date_of_incident')->nullable();
             $table->integer('incident_type')->nullable();
             $table->integer('severity')->nullable();
             $table->integer('reported_by')->nullable();
             $table->integer('odometer_reading')->nullable();
             $table->integer('status')->nullable();
             $table->string('description')->nullable();
             $table->string('claim_number')->nullable();
             $table->double('Cost')->nullable(); 
             $table->string('document')->nullable();
             $table->string('document1')->nullable();
             $table->string('Name')->nullable();
             $table->integer('vehicleID')->nullable()->unsigned()->index();
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
        Schema::dropIfExists('vehicle_incidents');
    }
}
