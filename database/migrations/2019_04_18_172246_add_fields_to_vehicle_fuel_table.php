<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToVehicleFuelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicle_fuel_log', function ($table) {
             $table->double('actual_km_reading')->nullable();
             $table->double('actual_hr_reading')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_fuel_log', function ($table) {
            $table->dropColumn('actual_km_reading');
            $table->dropColumn('actual_hr_reading');
      });
    }
}
