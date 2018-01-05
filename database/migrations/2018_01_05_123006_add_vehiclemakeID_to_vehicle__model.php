<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVehiclemakeIDToVehicleModel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //vehicle_model
        Schema::table('vehicle_model', function (Blueprint $table) {
            $table->integer('vehiclemake_id')->unsigned()->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_model', function (Blueprint $table) {
            $table->dropColumn('vehiclemake_id');
        });
    }
}
