<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistrationAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registration_areas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('registration_id')->unsigned()->index()->nullable();
            $table->integer('area_id')->unsigned()->index()->nullable();
            $table->double('result')->nullable();
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
        Schema::dropIfExists('registration_areas');
    }
}
