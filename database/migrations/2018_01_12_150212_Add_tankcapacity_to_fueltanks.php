<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTankcapacityToFueltanks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fuel_tanks', function (Blueprint $table) {
            //$table->double('tank_capacity')->nullable();
            //$table->double('current_fuel_litres')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fuel_tanks', function (Blueprint $table) {
           // $table->dropColumn('tank_capacity');
            //$table->dropColumn('current_fuel_litres');
        });
    }
}
