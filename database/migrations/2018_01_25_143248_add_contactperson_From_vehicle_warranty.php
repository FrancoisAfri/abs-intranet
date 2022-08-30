<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContactpersonFromVehicleWarranty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
     Schema::table('vehicle_warranties', function (Blueprint $table) {
            $table->unsignedInteger('contact_person')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_warranties', function (Blueprint $table) {
             $table->dropColumn('contact_person');
        });
    }
}
