<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddErsTokenToLeaveConfigurationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_configuration', function ($table) {
            $table->string('ers_token_number')->nullable();
            $table->Integer('number_of_days_before_automate_application')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leave_configuration', function ($table) {
            $table->dropColumn('ers_token_number');
            $table->dropColumn('number_of_days_before_automate_application');
        });
    }
}
