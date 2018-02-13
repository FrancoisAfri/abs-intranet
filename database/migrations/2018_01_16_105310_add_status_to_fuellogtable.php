<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToFuellogtable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $table->smallInteger('status');
     Schema::table('vehicle_fuel_log', function (Blueprint $table) {
             $table->smallInteger('status');
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
            $table->dropColumn('status');
         });
     }
 }