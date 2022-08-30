<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToVehicleDocumets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('vehicle_documets', function (Blueprint $table) {
            $table->unsignedInteger('expiry_type')->nullable()->index();
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
            $table->dropColumn('expiry_type');
        });
    }
}

