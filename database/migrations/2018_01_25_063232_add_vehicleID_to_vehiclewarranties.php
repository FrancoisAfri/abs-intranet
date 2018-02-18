<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVehicleIDToVehiclewarranties extends Migration
{
    /**
     * Run the migrations. 
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicle_warranties', function (Blueprint $table) {
            $table->integer('vehicleID')->index()->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_warranties', function (Blueprint $table) {
            $table->dropColumn('vehicleID');
        });
    }
}
