<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToFuelToUpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fuel_tank_topUp', function (Blueprint $table) {
            $table->string('make_or_model')->nullable()->unsigned()->index();
            $table->string('registration_number')->nullable()->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fuel_tank_topUp', function (Blueprint $table) {
            $table->dropColumn('make_or_model');
            $table->dropColumn('registration_number');
        });
    }
}