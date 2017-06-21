<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToMinutesMeeetingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('meeting_minutes', function($table) {
            $table->string('meeting_name')->nullable();
            $table->bigInteger('meeting_date')->nullable();
            $table->string('meeting_location')->nullable();
            $table->string('meeting_agenda')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meeting_minutes', function($table) {
            $table->dropColumn('meeting_name');
            $table->dropColumn('meeting_date');
            $table->dropColumn('meeting_location');
            $table->dropColumn('meeting_agenda');
        });
    }
}
