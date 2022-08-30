<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CollectTimestampToVehicleBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicle_booking', function (Blueprint $table) {
            $table->bigInteger('collect_timestamp')->nullable()->unsigned()->index();
            $table->bigInteger('return_timestamp')->nullable()->unsigned()->index();
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
            $table->bigInteger('collect_timestamp')->nullable()->unsigned()->index();
            $table->bigInteger('return_timestamp')->nullable()->unsigned()->index();
        });
    }
}
