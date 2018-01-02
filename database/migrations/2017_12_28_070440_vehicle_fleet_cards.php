<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VehicleFleetCards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('vehicle_fleet_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('card_type_id')->unsigned()->index()->nullable();
            $table->integer('company_id')->unsigned()->index()->nullable();
            $table->integer('holder_id')->unsigned()->index()->nullable();
            $table->string('card_number')->nullable();
            $table->bigInteger('expiry_date')->nullable();
            $table->integer('cvs_number')->nullable();
            $table->bigInteger('issued_date')->nullable();
            $table->integer('registration_number')->nullable();
            $table->integer('fleet_number')->nullable();
            $table->smallInteger('status')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_fleet_cards');
    }
}
