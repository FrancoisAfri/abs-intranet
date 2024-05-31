<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreFieldsStaffLoanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_loans', function ($table) {
			$table->bigInteger('rejected_hr_date')->nullable();
			$table->bigInteger('hr_approval_date')->nullable();
            $table->integer('hr_approval')->unsigned()->nullable();
            $table->string('hr_rejecttion_reason')->unsigned()->nullable();
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
            $table->dropColumn('rejected_hr_date');
            $table->dropColumn('hr_approval_date');
            $table->dropColumn('hr_approval');
            $table->dropColumn('hr_rejecttion_reason');
        });
    }
}
