<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDirectoryStaffLoanSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_loan_setups', function (Blueprint $table) {
            $table->string('loan_upload_directory')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff_loan_setups', function (Blueprint $table) {
            $table->dropColumn('loan_upload_directory');
        });
    }
}
