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
            $table->bigInteger('division_level_1')->index()->unsigned()->nullable();
            $table->bigInteger('division_level_2')->index()->unsigned()->nullable();
            $table->bigInteger('division_level_3')->index()->unsigned()->nullable();
            $table->bigInteger('division_level_4')->index()->unsigned()->nullable();
            $table->bigInteger('division_level_5')->index()->unsigned()->nullable();
            $table->string('tank_name')->nullable();
            $table->string('tank_location')->nullable();
            $table->string('tank_description')->nullable();
            $table->integer('tank_capacity')->unsigned()->index()->nullable();
            $table->integer('tank_manager')->index()->unsigned()->nullable();
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

