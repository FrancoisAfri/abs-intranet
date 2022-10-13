<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManualClockinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manual_clockins', function (Blueprint $table) {
            $table->increments('id');
			$table->uuid('uuid')->index();
			$table->integer('ip_address')->nullable();
			$table->integer('hr_id')->nullable();
			$table->integer('clockin_type')->nullable();
			$table->bigInteger('clockin_time')->nullable();
			$table->string('location')->nullable();
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
        Schema::dropIfExists('manual_clockins');
    }
}
