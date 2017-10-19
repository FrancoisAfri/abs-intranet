<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_configuration', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('allow_sending_messages')->nullable();
            $table->smallInteger('use_fleet_number')->nullable();
            $table->smallInteger('include_inspection_document')->nullable();
            $table->smallInteger('new_vehicle_approval')->nullable();
            $table->smallInteger('include_division_report')->nullable();
            $table->smallInteger('fuel_auto_approval')->nullable();
            $table->smallInteger('fuel_require_tank_manager_approval')->nullable();
            $table->smallInteger('fuel_require_ceo_approval')->nullable();
            $table->smallInteger('mechanic_sms')->nullable();
            $table->integer('permit_days')->nullable();
            $table->string('currency')->nullable();
            $table->smallInteger('approval_manager_capturer')->nullable();
            $table->smallInteger('approval_manager_driver')->nullable();
            $table->smallInteger('approval_hod')->nullable();
            $table->smallInteger('approval_admin')->nullable();
            $table->smallInteger('return_due_manager')->nullable();
            $table->smallInteger('return_due_hod')->nullable();
            $table->smallInteger('return_due_admin')->nullable();
            $table->smallInteger('fines_manager')->nullable();
            $table->smallInteger('fines_hod')->nullable();
            $table->smallInteger('fines_admin')->nullable();
            $table->smallInteger('incident_minor_manager')->nullable();
            $table->smallInteger('incident_major_manager')->nullable();
            $table->smallInteger('incident_critical_manager')->nullable();
            $table->smallInteger('incident_minor_hod')->nullable();
            $table->smallInteger('incident_major_hod')->nullable();
            $table->smallInteger('incident_critical_hod')->nullable();
            $table->smallInteger('incident_minor_admin')->nullable();
            $table->smallInteger('incident_major_admin')->nullable();
            $table->smallInteger('incident_critical_admin')->nullable();
            $table->smallInteger('submit_on_behalf')->nullable();
            $table->smallInteger('allow_past_bookings')->nullable();
            $table->string('notification_method')->nullable();
            $table->integer('service_days')->nullable();
            $table->integer('service_km')->nullable();
            $table->integer('service_overdue_days')->nullable();
            $table->integer('service_overdue_km')->nullable();
            $table->integer('no_bookings_days')->nullable();
            $table->integer('no_bookings_km')->nullable();
            $table->smallInteger('no_bookings_minor')->nullable();
            $table->smallInteger('no_bookings_major')->nullable();
            $table->smallInteger('no_bookings_critical')->nullable();
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
        Schema::dropIfExists('vehicle_configuration');
    }
}
