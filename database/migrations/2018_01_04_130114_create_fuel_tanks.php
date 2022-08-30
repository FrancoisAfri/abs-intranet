<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFuelTanks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuel_tanks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('division_level_1')->index()->nullable();
            $table->unsignedBigInteger('division_level_2')->index()->nullable();
            $table->unsignedBigInteger('division_level_3')->index()->nullable();
            $table->unsignedBigInteger('division_level_4')->index()->nullable();
            $table->unsignedBigInteger('division_level_5')->index()->nullable();
            $table->string('tank_name')->nullable();
            $table->string('tank_location')->nullable();
            $table->string('tank_description')->nullable();
            $table->unsignedInteger('tank_capacity')->index()->nullable();
            $table->unsignedInteger('tank_manager')->index()->nullable();
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
        Schema::dropIfExists('fuel_tanks');
    }

}

