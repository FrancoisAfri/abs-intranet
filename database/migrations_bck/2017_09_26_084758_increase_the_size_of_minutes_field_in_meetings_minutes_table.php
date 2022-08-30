<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IncreaseTheSizeOfMinutesFieldInMeetingsMinutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meetings_minutes', function (Blueprint $table) {
            $table->string('minutes', 1000)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meetings_minutes', function (Blueprint $table) {
            $table->string('minutes', 500)->change();
        });
    }
}
