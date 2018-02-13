<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCurrentDateToFuellog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicle_fuel_log', function (Blueprint $table) {
    $table->timestamp('published_at')->nullable();;
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
         $table->dropColumn('published_at');
    });
}
}