<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLeaveHistoryColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_history', function ($table) {
            $table->double('transcation')->unsigned()->index()->nullable();
            $table->double('previous_balance')->unsigned()->index()->nullable();
            $table->double('current_balance')->unsigned()->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leave_history', function ($table) {
            $table->dropColumn('transcation');
            $table->dropColumn('previous_balance');
            $table->dropColumn('current_balance');
        });
    }
}
