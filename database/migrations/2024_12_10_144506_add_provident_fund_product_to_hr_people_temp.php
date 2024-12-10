<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProvidentFundProductToHrPeopleTemp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_people_temp', function ($table) {
            //
            $table->string('provident_fund_product')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hr_people_temp', function ($table) {
            //
            $table->dropColumn('provident_fund_product');
        });
    }
}
