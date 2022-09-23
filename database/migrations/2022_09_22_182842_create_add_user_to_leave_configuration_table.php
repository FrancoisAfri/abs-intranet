<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddUserToLeaveConfigurationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //hr_person_id
        Schema::table('leave_configuration', function($table) {
            $table->unsignedBigInteger('hr_person_id')->default(0)->nullable();
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
            $table->dropColumn('hr_person_id');
        });
    }
}
