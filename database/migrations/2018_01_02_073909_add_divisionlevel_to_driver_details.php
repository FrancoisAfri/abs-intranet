<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDivisionlevelToDriverDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('drver_details', function (Blueprint $table) {
            $table->unsignedInteger('division_level_5')->index()->nullable();
            $table->unsignedInteger('division_level_4')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('drver_details', function (Blueprint $table) {
            $table->dropColumn('division_level_5');
            $table->dropColumn('division_level_4');
        });
    }
}
