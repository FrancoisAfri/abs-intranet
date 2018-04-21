<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatejobcardMaintananceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
       Schema::create('jobcard_maintanance', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('vehicle_id')->unsigned()->index()->nullable();
            $table->bigInteger('card_date')->nullable();
			$table->bigInteger('schedule_date')->nullable();
			$table->bigInteger('booking_date')->nullable();
			$table->integer('supplier_id')->unsigned()->index()->nullable();
			$table->integer('service_type')->unsigned()->index()->nullable();
			$table->Integer('estimated_hours')->nullable();
			$table->string('service_file_upload')->nullable();
			$table->bigInteger('service_time')->nullable();
			$table->Integer('machine_hour_metre')->nullable();
			$table->Integer('machine_odometer')->nullable();
			$table->Integer('last_driver_id')->unsigned()->index()->nullable();
			$table->string('inspection_info')->nullable();
			$table->string('inspection_file_upload')->nullable();
			$table->Integer('mechanic_id')->unsigned()->index()->nullable();
			$table->string('instruction')->nullable();
            $table->smallInteger('status')->nullable();
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

        Schema::dropIfExists('jobcard_maintanance');

    }

}

