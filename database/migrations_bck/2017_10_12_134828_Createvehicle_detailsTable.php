<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatevehicleDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->smallinteger('status')->nullable();
            $table->integer('responsible_for_maintenance')->unsigned()->index()->nullable();
            $table->integer('vehicle_make')->unsigned()->index()->nullable();
            $table->integer('vehicle_model')->unsigned()->index()->nullable();
            $table->integer('vehicle_type')->unsigned()->index()->nullable();
            $table->string('year')->nullable();
            $table->string('vehicle_registration')->nullable();
            $table->string('chassis_number')->nullable();
            $table->string('engine_number')->nullable();
            $table->string('vehicle_color')->nullable();
            $table->integer('metre_reading_type')->nullable();
            $table->string('odometer_reading')->nullable();
            $table->string('hours_reading')->nullable();
            $table->integer('fuel_type')->unsigned()->index()->nullable();
            $table->string('size_of_fuel_tank')->nullable();
            $table->string('fleet_number')->nullable();
            $table->string('cell_number')->nullable();
            $table->string('tracking_umber')->nullable();
            $table->integer('vehicle_owner')->unsigned()->index()->nullable();
            $table->integer('title_type')->nullable();
            $table->integer('financial_institution')->nullable();
            $table->integer('company')->nullable();
            $table->string('extras')->nullable();
            $table->string('image')->nullable();
            $table->string('registration_papers')->nullable();
            $table->integer('property_type')->unsigned()->index()->nullable();
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
        Schema::dropIfExists('vehicle_details');
    }
}