<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecurringMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recurring_meetings', function (Blueprint $table) {
            $table->increments('id');
			$table->string('meeting_title')->nullable();
			$table->bigInteger('meeting_date')->nullable();
            $table->string('meeting_location')->nullable();
            $table->string('meeting_agenda')->nullable();
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
        Schema::dropIfExists('recurring_meetings');
    }
}
