<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Jobcardnotestable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
     Schema::create('jobcard_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('vehicle_id')->index()->nullable();
            $table->unsignedInteger('jobcard_id')->index()->nullable();
            $table->String('note_details')->index()->nullable();
            $table->unsignedInteger('user_id')->index()->nullable();
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
        Schema::dropIfExists('jobcard_notes');
    }

}