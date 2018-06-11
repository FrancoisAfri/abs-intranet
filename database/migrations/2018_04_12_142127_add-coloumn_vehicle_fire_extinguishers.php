<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColoumnVehicleFireExtinguishers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicle_fire_extinguisher', function (Blueprint $table) {
           $table->integer('capturer_id')->unsigned()->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fire_extinguishers', function (Blueprint $table) {
            $table->dropColumn('vehicle_fire_extinguisher');
        });
    }
}
