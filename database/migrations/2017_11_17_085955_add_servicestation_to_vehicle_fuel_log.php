<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddServicestationToVehicleFuelLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('vehicle_fuel_log', function ($table) {
             $table->integer('service_station')->nullable()->unsigned()->index();
             $table->integer('transaction_type')->nullable();
             $table->double('cost_per_litre')->nullable();
             $table->double('total_cost')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('vehicle_fuel_log', function ($table) {
            $table->dropColumn('service_station');
            $table->dropColumn('transaction_type');
            $table->dropColumn('cost_per_litre');
            $table->dropColumn('total_cost');
        });
    }
}


 