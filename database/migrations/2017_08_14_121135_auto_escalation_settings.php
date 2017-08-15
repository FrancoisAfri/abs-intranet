<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AutoEscalationSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_escalation_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('auto_low')->nullable();
            $table->smallInteger('office_hrs_low')->nullable();
            $table->integer('notify_level_low')->nullable();
            $table->smallInteger('office_hrs_low_email')->nullable();
            $table->smallInteger('office_hrs_low_sms')->nullable();
            $table->smallInteger('aftoffice_hrs_low_email')->nullable();
            $table->smallInteger('aftoffice_hrs_low_sms')->nullable();
            $table->integer('auto_mormal')->nullable();
            $table->smallInteger('office_hrs_normal')->nullable();
            $table->integer('notify_level_normal')->nullable();
            $table->smallInteger('office_hrs_normal_email')->nullable();
            $table->smallInteger('office_hrs_normal_sms')->nullable();
            $table->smallInteger('aftoffice_hrs_normal_email')->nullable();
            $table->smallInteger('aftoffice_hrs_normal_sms')->nullable();
            $table->integer('auto_high')->nullable();
            $table->smallInteger('office_hrs_hihg')->nullable();
            $table->integer('notify_level_high')->nullable();
            $table->smallInteger('office_hrs_high_email')->nullable();
            $table->smallInteger('office_hrs_high_sms')->nullable();
            $table->smallInteger('aftoffice_hrs_high_email')->nullable();
            $table->smallInteger('aftoffice_hrs_high_sms')->nullable();
            $table->integer('auto_critical')->nullable();
            $table->smallInteger('office_hrs_critical')->nullable();
            $table->integer('notify_level_critical')->nullable();
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
         Schema::dropIfExists('auto_escalation_settings');
    }
}
