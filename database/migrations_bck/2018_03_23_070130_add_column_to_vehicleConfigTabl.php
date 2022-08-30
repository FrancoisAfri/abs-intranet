<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToVehicleConfigTabl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicle_configuration', function (Blueprint $table) {
            $table->smallInteger('inforce_vehicle_image')->nullable()->unsigned()->index();
            $table->smallInteger('inforce_vehicle_documents')->nullable()->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_configuration', function (Blueprint $table) {
            $table->dropColumn('inforce_vehicle_image');
            $table->dropColumn('inforce_vehicle_documents');
        });
    }
}
