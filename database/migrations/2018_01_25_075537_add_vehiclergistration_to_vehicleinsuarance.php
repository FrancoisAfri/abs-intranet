<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVehiclergistrationToVehicleinsuarance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicle_insurance', function (Blueprint $table) {
            $table->unsignedInteger('vehicle_reg_no')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('vehicle_insurance', function (Blueprint $table) {
            $table->dropColumn('vehicle_reg_no');
        });
    }
}
