<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTankAndOtherToFuellogtable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicle_fuel_log', function (Blueprint $table) {
            $table->integer('tank_and_other')->nullable();
            $table->integer('transaction_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_fuel_log', function (Blueprint $table) {
            $table->dropColumn('tank_and_other');
            $table->dropColumn('transaction_type');
            
        });
    }
}
 