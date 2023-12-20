<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_loans', function (Blueprint $table) {
            $table->increments('id');
			$table->uuid('uuid')->index();
			$table->unsignedInteger('type')->nullable();
			$table->unsignedInteger('hr_id')->nullable();
			$table->unsignedInteger('repayment_month')->nullable();
			$table->unsignedInteger('status')->nullable();
			$table->decimal('amount', 9, 2)->default(0);
			$table->string('reason')->nullable();
			$table->string('rejection_reason')->nullable();
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
        Schema::dropIfExists('staff_loans');
    }
}
