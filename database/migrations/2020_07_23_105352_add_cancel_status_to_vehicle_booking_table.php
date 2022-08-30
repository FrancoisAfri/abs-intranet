<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCancelStatusToVehicleBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicle_booking', function (Blueprint $table) {
            $table->unsignedInteger('cancel_status')->nullable()->index();
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
            $table->unsignedInteger('cancel_status')->nullable()->index();
        });
    }
}
