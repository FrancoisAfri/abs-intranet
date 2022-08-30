<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLitresVehicleGeneralcostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('vehicle_generalcosts', function (Blueprint $table) {
            $table->double('litres_new')->nullable()->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('vehicle_generalcosts', function (Blueprint $table) {
            $table->dropColumn('litres_new');
		});
    }
}
