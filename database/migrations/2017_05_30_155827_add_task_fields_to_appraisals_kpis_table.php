<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTaskFieldsToAppraisalsKpisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appraisals_kpis', function($table) {
            $table->smallInteger('is_task_kpi')->nullable();
            $table->smallInteger('kpi_task_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appraisals_kpis', function($table) {
            $table->dropColumn('is_task_kpi');
            $table->dropColumn('kpi_task_type');
        });
    }
}
