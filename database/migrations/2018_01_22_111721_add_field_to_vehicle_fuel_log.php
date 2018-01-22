<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToVehicleFuelLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicle_fuel_log', function (Blueprint $table) {
            $table->smallInteger('status');
            $table->string('reject_reason')->nullable();
            $table->bigInteger('reject_timestamp')->nullable();
            $table->integer('rejector_id')->nullable();
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
            $table->string('status')->nullable();
            $table->string('reject_reason')->nullable();
            $table->bigInteger('reject_timestamp')->nullable();
            $table->integer('rejector_id')->nullable();
        });
    }
}
