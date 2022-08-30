<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_booking', function (Blueprint $table) {
            $table->increments('id');
            $table->string('capturer_id')->nullable()->unsigned()->index();
            $table->integer('driver_id')->nullable()->unsigned()->index();
            $table->string('purpose')->nullable();
            $table->integer('status')->nullable();
            $table->integer('vehicle_id')->nullable();
            $table->string('reject_reason')->nullable();
            $table->bigInteger('require_datetime')->nullable();
            $table->integer('project_id')->nullable();
            $table->string('destination')->nullable();
            $table->integer('start_mileage_id')->nullable();
            $table->integer('end_mileage_id')->nullable();
            $table->integer('approver1_id')->nullable();
            $table->bigInteger('approver1_timestamp')->nullable();
            $table->integer('approver2_id')->nullable();
            $table->bigInteger('approver2_timestamp')->nullable();
            $table->integer('approver3_id')->nullable();
            $table->bigInteger('approver3_timestamp')->nullable();
            $table->integer('rejector_id')->nullable();
            $table->bigInteger('rejector_timestamp')->nullable();
            $table->bigInteger('return_datetime')->nullable();
            $table->bigInteger('actual_from_datetime')->nullable();
            $table->bigInteger('actual_to_datetime')->nullable();
            $table->smallinteger('booking_type')->nullable();
            $table->integer('approver4_id')->nullable();
            $table->integer('collector_id')->nullable();
            $table->integer('returner_id')->nullable();
            $table->integer('canceller_id')->nullable();
            $table->bigInteger('canceller_timestamp')->nullable();
            $table->bigInteger('approver4_timestamp')->nullable();
            $table->string('return_time')->nullable();
            $table->integer('usage_type')->nullable();
            $table->integer('vehicle_make')->nullable()->unsigned()->index();
            $table->integer('vehicle_type')->nullable()->unsigned()->index();
            $table->integer('vehicle_model')->nullable()->unsigned()->index();
            $table->string('vehicle_reg')->nullable()->unsigned()->index();
            $table->integer('UserID')->nullable()->unsigned()->index();

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
        Schema::dropIfExists('vehicle_booking');
    }
}
