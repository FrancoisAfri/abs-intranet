<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNegativeDaysToLeaveConfigurationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('leave_configuration', function ($table) {
            //
            $table->integer('annual_negative_days')->nullable();
            $table->integer('sick_negative_days')->nullable();
            $table->integer('number_of_days_sick')->nullable();
            $table->integer('number_of_days_annual')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leave_configuration', function ($table) {
            //
            $table->dropColumn('annual_negative_days');
            $table->dropColumn('sick_negative_days');
            $table->dropColumn('number_of_days_sick');
            $table->dropColumn('number_of_days_annual');
        });
    }
}
