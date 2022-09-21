<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNumberOfDaysUntilEscalationToLeaveConfigurationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_configuration', function($table) {
            $table->unsignedBigInteger('number_of_days_to_remind_manager')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leave_configuration', function($table) {
            $table->dropColumn('number_of_days_to_remind_manager');
        });
    }
}
