<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDueTimeToEmployeeTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_tasks', function (Blueprint $table) {
             $table->bigInteger('due_time')->index()->unsigned()->nullable();
		});	 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_tasks', function (Blueprint $table) {
			 $table->dropColumn('due_time');
        });
    }
}
