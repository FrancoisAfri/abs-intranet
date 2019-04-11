<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHrPersonToDivisionLevelOnes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('division_level_ones', function($table) {
            $table->integer('hr_person_id')->unsigned()->index()->nullable();
            $table->integer('payrollPerson_id')->unsigned()->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('division_level_ones', function($table) {
				   $table->dropColumn('hr_person_id');
				   $table->dropColumn('payrollPerson_id');
		}); 
	}
}