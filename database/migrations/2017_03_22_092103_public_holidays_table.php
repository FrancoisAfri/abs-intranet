<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PublicHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_holidays', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('day')->index()->nullable();
            $table->unsignedInteger('country_id')->index()->nullable();
            // $table->foreign('country_id')
            //     ->references('id')
            //     ->on('countries');
            $table->integer('year')->index()->nullable();
            $table->string('name')->index()->nullable();
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
        Schema::dropIfExists('public_holidays');
    }
}
