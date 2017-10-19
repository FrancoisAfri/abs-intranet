<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVehicleMakeToVehicleMaintenanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicle_maintenance', function (Blueprint $table) {
            $table->integer('loader_id')->nullable();
            $table->integer('vehicle_make')->nullable();
            $table->integer('vehicle_model')->nullable();
            $table->integer('vehicle_type')->nullable();
            $table->string('year')->nullable();
            $table->string('vehicle_registration')->nullable();
            $table->string('chassis_number')->nullable();
            $table->string('engine_number')->nullable();
            $table->string('vehicle_color')->nullable();
            $table->integer('metre_reading_type')->nullable();
            $table->string('odometer_reading')->nullable();
            $table->integer('hours_reading')->nullable();
            $table->integer('fuel_type')->nullable();
            $table->integer('size_of_fuel_tank')->nullable();
            $table->integer('fleet_number')->nullable();
            $table->string('cell_number')->nullable();
            $table->integer('tracking_umber')->nullable();
            $table->integer('vehicle_owner')->nullable();
            $table->integer('title_type')->nullable();
            $table->integer('financial_institution')->nullable();
            $table->integer('company')->nullable();
            $table->string('extras')->nullable();
            $table->string('image')->nullable();
            $table->string('registration_papers')->nullable();
            $table->integer('property_type')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_maintenance', function (Blueprint $table) {
            $table->dropColumn('loader_id');
            $table->dropColumn('vehicle_make');
            $table->dropColumn('vehicle_model');
            $table->dropColumn('vehicle_type');
            $table->dropColumn('year');
            $table->dropColumn('vehicle_registration');
            $table->dropColumn('chassis_number');
            $table->dropColumn('engine_number');
            $table->dropColumn('vehicle_color');
            $table->dropColumn('metre_reading_type');
            $table->dropColumn('odometer_reading');
            $table->dropColumn('hours_reading');
            $table->dropColumn('fuel_type');
            $table->dropColumn('size_of_fuel_tank');
            $table->dropColumn('fleet_number');
            $table->dropColumn('cell_number');
            $table->dropColumn('tracking_umber');
            $table->dropColumn('vehicle_owner');
            $table->dropColumn('title_type');
            $table->dropColumn('financial_institution');
            $table->dropColumn('company');
            $table->dropColumn('extras');
            $table->dropColumn('image');
            $table->dropColumn('registration_papers');
            $table->dropColumn('property_type');
        });
    }
}
