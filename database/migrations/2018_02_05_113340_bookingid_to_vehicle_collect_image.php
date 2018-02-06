<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BookingidToVehicleCollectImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicle_collect_image', function (Blueprint $table) {
            $table->timestamp('bookingID')->nullable();;
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
            $table->timestamp('bookingID')->nullable();;
        });
    }
}