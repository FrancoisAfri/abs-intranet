<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVehicleyearToVehicleBooking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicle_booking', function ($table) {
            $table->string('year')->nullable()->unsigned()->index();
            $table->string('fleet_number')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_booking', function ($table) {
            $table->dropColumn('year');
            $table->dropColumn('fleet_number');
        });
    }
}
