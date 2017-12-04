<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExpDateToVehicleDocumenets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicle_documets', function ($table) {
             $table->bigInteger('exp_date')->nullable();
             $table->bigInteger('date_from')->nullable();
             $table->integer('type')->nullable();
             $table->integer('role')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('vehicle_documets', function ($table) {
            $table->dropColumn('exp_date');
            $table->dropColumn('date_from');
            $table->dropColumn('type');
            $table->dropColumn('role');
        });
    }
}
