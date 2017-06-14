<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDepItToTaskLibrayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_libraries', function($table) {
            $table->integer('dept_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
	
    public function down()
    {
         Schema::table('task_libraries', function($table) {
            $table->dropColumn('dept_id');
        });
    }
}
