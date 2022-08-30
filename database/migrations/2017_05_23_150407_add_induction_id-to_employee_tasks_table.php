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
            $table->unsignedInteger('induction_id')->nullable()->index();
            $table->unsignedInteger('meeting_id')->nullable()->index();
            $table->unsignedInteger('is_dependent')->nullable()->index();
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
