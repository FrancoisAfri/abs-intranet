<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecurringMeetingsAttendeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recurring_meetings_attendees', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('employee_id')->nullable();
			$table->integer('client_id')->nullable();
			$table->integer('meeting_id')->nullable();
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
        Schema::dropIfExists('recurring_meetings_attendees');
    }
}
