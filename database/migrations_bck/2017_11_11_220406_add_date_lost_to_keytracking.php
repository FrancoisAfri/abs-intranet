<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDateLostToKeytracking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicle_documets', function ($table) {
             $table->bigInteger('date_lost')->nullable();
             $table->string('reason_loss')->nullable();
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
            $table->dropColumn('date_lost');
            $table->dropColumn('reason_loss');
        });
    }
}
