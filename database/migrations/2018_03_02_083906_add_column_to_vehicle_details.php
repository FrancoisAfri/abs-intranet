<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToVehicleDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('vehicle_details', function (Blueprint $table) {
//            $table->Integer('booking_status')->nullable();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('cms_news_ratings', function (Blueprint $table) {
//            $table->dropColumn('booking_status');
//        });
    }
}
