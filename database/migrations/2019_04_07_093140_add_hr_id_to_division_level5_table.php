<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHrIdToDivisionLevel5Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('division_level_fives', function($table) {
            $table->unsignedInteger('hr_manager_id')->index()->nullable();
            $table->unsignedInteger('payroll_officer')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('division_level_fives', function($table) {
		   $table->dropColumn('hr_manager_id');
		   $table->dropColumn('payroll_officer');
		});
    }
}
