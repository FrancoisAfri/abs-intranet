<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDateToVehicleDocumets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('vehicle_documets', function (Blueprint $table) {
            $table->bigInteger('currentdate')->nullable()->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('vehicle_documets', function (Blueprint $table) {
            $table->dropColumn('currentdate');
        });
    }
}
