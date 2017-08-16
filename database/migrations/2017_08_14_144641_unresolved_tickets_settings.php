<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UnresolvedTicketsSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('unresolved_tickets_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tickets_low')->nullable();
            $table->smallInteger('low_ah')->nullable();
            $table->smallInteger('esc_low_email')->nullable();
            $table->smallInteger('esc_low_sms')->nullable();
            $table->smallInteger('aftoffice_hrs_low_email')->nullable();
            $table->smallInteger('aftoffice_hrs_low_sms')->nullable();
         
            $table->integer('tickets_normal')->nullable();
            $table->smallInteger('normal_oficehrs')->nullable();
            $table->smallInteger('office_hrs_normal_email')->nullable();
            $table->smallInteger('office_hrs_normal_sms')->nullable();
            $table->smallInteger('aftoffice_hrs_nomal_email')->nullable();
            $table->smallInteger('aftoffice_hrs_nomal_sms')->nullable();

            $table->integer('tickets_high')->nullable();
            $table->smallInteger('high_oficehrs')->nullable();
            $table->smallInteger('office_hrs_high_email')->nullable();
            $table->smallInteger('office_hrs_high_sms')->nullable();
            $table->smallInteger('aftoffice_hrs_high_email')->nullable();
            $table->smallInteger('aftoffice_hrs_high_sms')->nullable();

            $table->integer('auto_critical')->nullable();
            $table->smallInteger('office_hrs_critical')->nullable();
            $table->smallInteger('notify_level_critical')->nullable();
            $table->smallInteger('office_hrs_critical_email')->nullable();
            $table->smallInteger('office_hrs_critical_sms')->nullable();
            $table->smallInteger('aftoffice_hrs_critical_email')->nullable();
            $table->smallInteger('aftoffice_hrs_critical_sms')->nullable();
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
        Schema::dropIfExists('unresolved_tickets_settings');
    }
}
