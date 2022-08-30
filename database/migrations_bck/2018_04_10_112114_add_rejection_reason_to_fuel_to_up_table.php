<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRejectionReasonToFuelToUpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fuel_tank_topUp', function (Blueprint $table) {
            $table->string('reject_reason')->nullable()->unsigned()->index();
            $table->bigInteger('reject_timestamp')->nullable()->unsigned()->index();
            $table->integer('rejector_id')->nullable()->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fuel_tank_topUp', function (Blueprint $table) {
            $table->dropColumn('reject_reason');
            $table->dropColumn('reject_timestamp');
            $table->dropColumn('rejector_id');
        });
    }
}
