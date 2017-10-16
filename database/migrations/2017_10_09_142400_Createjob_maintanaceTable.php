<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatejobMaintanaceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_maintanace', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vehicle')->unsigned()->index()->nullable();
            $table->integer('service_days')->nullable();
            $table->bigInteger('job_card_date')->nullable();
            $table->bigInteger('schedule_date')->nullable();
            $table->bigInteger('booking_date')->nullable();
            $table->integer('supplier')->unsigned()->index()->nullable();
            $table->integer('service_type')->unsigned()->index()->nullable();
            $table->integer('estimated_hours')->nullable();
            $table->string('service_docs')->nullable();
            $table->integer('service_time')->nullable();
            $table->integer('machine_hour_metre')->nullable();
            $table->integer('machine_odometer')->nullable();
            $table->integer('last_driver')->unsigned()->index()->nullable();
            $table->string('inspection_info')->nullable();
            $table->string('inspection_docs')->nullable();
            $table->integer('mechanic')->unsigned()->index()->nullable();
            $table->integer('emails')->unsigned()->index()->nullable();
            $table->string('instruction')->nullable();
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
        Schema::dropIfExists('job_maintanace');
    }
}
