<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffLoanSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_loan_setups', function (Blueprint $table) {
            $table->increments('id');
			$table->uuid('uuid')->index();
			$table->unsignedInteger('first_approval')->nullable();
			$table->unsignedInteger('second_approval')->nullable();
			$table->unsignedInteger('hr')->nullable();
			$table->unsignedInteger('payroll')->nullable();
			$table->unsignedInteger('finance')->nullable();
			$table->unsignedInteger('finance_second')->nullable();
			$table->decimal('max_amount', 9, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff_loan_setups');
    }
}
