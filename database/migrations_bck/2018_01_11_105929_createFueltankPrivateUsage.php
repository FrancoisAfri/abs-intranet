<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFueltankPrivateUsage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuel_tank_privateUsage', function (Blueprint $table) {
            $table->increments('id');
            $table->string('document_no')->nullable();
            $table->bigInteger('document_date')->nullable();
            $table->bigInteger('usage_date')->nullable();
            $table->integer('type')->nullable();
            $table->string('make_or_model')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('description')->nullable();
            $table->integer('received_by')->nullable();
            $table->integer('captured_by')->nullable();
            $table->integer('person_responsible')->unsigned()->index()->nullable();
            $table->smallInteger('status');
            $table->integer('tank_id')->unsigned()->index()->nullable();
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
        Schema::dropIfExists('fuel_tank_privateUsage');
    }
}