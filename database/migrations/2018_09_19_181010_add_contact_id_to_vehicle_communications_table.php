<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContactIdToVehicleCommunicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('fleet_communications', function ($table) {
            $table->unsignedInteger('contact_id')->index()->nullable();
            $table->unsignedInteger('company_id')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('fleet_communications', function ($table) {
            $table->dropColumn('contact_id');
            $table->dropColumn('company_id');
        });
    }
}