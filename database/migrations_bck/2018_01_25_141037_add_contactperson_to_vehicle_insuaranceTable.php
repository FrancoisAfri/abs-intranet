<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContactpersonToVehicleInsuaranceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
     Schema::table('vehicle_insurance', function (Blueprint $table) {
            $table->integer('contact_person')->index()->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_insurance', function (Blueprint $table) {
            $table->dropColumn('contact_person');
        });
    }
}
