<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDivisionLevel1ToVehicleMaintenance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('vehicle_maintenance', function ($table) {
            $table->integer('division_level_1')->nullable()->unsigned()->index();
            $table->integer('division_level_2')->nullable()->unsigned()->index();
            $table->integer('division_level_3')->nullable()->unsigned()->index();
            $table->integer('responsible')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('vehicle_maintenance', function ($table) {
            $table->dropColumn('division_level_1');
            $table->dropColumn('division_level_2');
            $table->dropColumn('division_level_3');
            $table->dropColumn('responsible');
        });
    }
}
