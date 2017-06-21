<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToMinutesAttendeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('meeting_attendees', function($table) {
			$table->integer('employee_id')->nullable();
			$table->integer('attendance')->nullable();
			$table->string('apology')->nullable();
			$table->integer('client_id')->nullable();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meeting_attendees', function($table) {
            $table->dropColumn('employee_id');
            $table->dropColumn('attendance');
            $table->dropColumn('apology');
            $table->dropColumn('client_id');
        });
    }
}
