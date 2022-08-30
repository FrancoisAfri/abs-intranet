<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFleetNumberToVehicleBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void 
     */
    public function up()
    {
        Schema::table('vehicle_booking', function (Blueprint $table) {
            $table->string('fleet_number')->nullable()->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_booking', function (Blueprint $table) {
            $table->string('fleet_number')->nullable()->unsigned()->index();
        });
    }
}
