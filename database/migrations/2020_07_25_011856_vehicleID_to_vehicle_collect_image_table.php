<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VehicleIDToVehicleCollectImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicle_collect_image', function (Blueprint $table) {
           $table->integer('vehicleID')->nullable()->unsigned()->index();
			$table->integer('bookingID')->nullable()->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_collect_image', function (Blueprint $table) {
        
			$table->dropColumn('vehicleID');
			$table->dropColumn('bookingID');
        });
    }
}
