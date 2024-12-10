<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProvidentFundProductToHrPeopleChanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_people_changes', function ($table) {
            //
            $table->string('provident_fund_product')->index()->nullable();
            $table->string('start_time')->index()->nullable();
            $table->string('end_time')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hr_people_changes', function ($table) {
            //
            $table->dropColumn('provident_fund_product');
            $table->dropColumn('start_time');
            $table->dropColumn('end_time');
        });
    }
}
