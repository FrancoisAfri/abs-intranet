<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInductionIdToEmployeeTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_tasks', function($table) {
            $table->integer('induction_id')->nullable()->unsigned()->index();
            $table->integer('meeting_id')->nullable()->unsigned()->index();
            $table->integer('is_dependent')->nullable()->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_tasks', function($table) {
            $table->dropColumn('induction_id');
            $table->dropColumn('meeting_id');
            $table->dropColumn('is_dependent');
        });
	}
}
