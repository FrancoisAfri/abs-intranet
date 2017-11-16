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
            $table->integer('responsible_for_maintenance')->nullable();
            $table->integer('vehicle_make')->unsigned()->index();
            $table->integer('vehicle_model')->unsigned()->index();
            $table->integer('vehicle_type')->unsigned()->index();
            $table->string('year')->nullable();
            $table->string('vehicle_registration')->nullable();
            $table->string('chassis_number')->nullable();
            $table->string('engine_number')->nullable();
            $table->string('vehicle_color')->nullable();
            $table->integer('metre_reading_type')->nullable();
            $table->string('odometer_reading')->nullable();
            $table->string('hours_reading')->nullable();
            $table->integer('fuel_type')->unsigned()->index();
            $table->integer('size_of_fuel_tank')->nullable();
            $table->string('fleet_number')->nullable();
            $table->string('cell_number')->nullable();
            $table->string('tracking_umber')->nullable();
            $table->integer('vehicle_owner')->unsigned()->index();
            $table->integer('title_type')->unsigned()->index();
            $table->integer('financial_institution')->unsigned()->index();
            $table->integer('company')->nullable();
            $table->string('extras')->nullable();
            $table->string('image')->nullable();
            $table->string('registration_papers')->nullable();
            $table->integer('property_type')->nullable();
            $table->integer('division_level_5')->unsigned()->index();
            $table->integer('division_level_4')->unsigned()->index();
            $table->bigInteger('currentDate')->nullable()->unsigned()->index();

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
            $table->dropColumn('responsible_for_maintenance');
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
            $table->dropColumn('division_level_5');
            $table->dropColumn('division_level_4');
            $table->dropColumn('currentDate');
        });
    }
}
