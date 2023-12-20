<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToStaffLoanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('staff_loans', function ($table) {
			$table->bigInteger('rejected_date')->nullable();
			$table->bigInteger('first_approval_date')->nullable();
			$table->bigInteger('second_approval_date')->nullable();
            $table->integer('first_approval')->unsigned()->nullable();
            $table->integer('second_approval')->unsigned()->nullable();
            $table->integer('rejected_by')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff_loans', function ($table) {
            $table->dropColumn('second_approval_date');
            $table->dropColumn('first_approval_date');
            $table->dropColumn('first_approval');
            $table->dropColumn('second_approval');
            $table->dropColumn('rejected_by');
            $table->dropColumn('rejected_date');
        });
    }
}
