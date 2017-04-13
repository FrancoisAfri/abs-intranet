<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStartDateToLeaveApplication extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('leave_application', function($table) {
            //$table->bigInteger('start_date')->nullable();
            //$table->bigInteger('end_date')->nullable();
            //$table->integer('status')->unsigned()->index()->nullable();
            $table->integer('hr_id')->nullable();
            $table->integer('leave_type_id')->nullable();
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('leave_application', function($table) {
            //$table->dropColumn('start_date');
            //$table->dropColumn('end_date');
            //$table->dropColumn('status');
            $table->dropColumn('hr_id');
            $table->dropColumn('leave_type_id');
          
        });
    }
}
