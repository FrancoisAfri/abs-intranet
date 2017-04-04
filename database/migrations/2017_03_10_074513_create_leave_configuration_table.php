<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveConfigurationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_configuration', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('allow_annualLeave_credit')->nullable();
            $table->smallInteger('allow_sickLeave_credit')->nullable();
            $table->smallInteger('show_non_employees_in_leave_Module')->nullable();
            $table->smallInteger('require_managers_approval')->nullable();
            $table->smallInteger('all_managers_to_approve')->nullable();
            $table->smallInteger('require_department_head_approval')->nullable();
            $table->smallInteger('require_hr_approval')->nullable();
            $table->smallInteger('require_payroll_approval')->nullable();
            $table->smallInteger('limit_administration_to_assigned_divisions')->nullable();
            $table->integer('mumber_of_days_until_escalation')->nullable();
            $table->smallInteger('document_compulsory_on_Study_leave_application')->nullable();
            $table->smallInteger('document_compulsory_when_two_sick_leave_8_weeks')->nullable();
            $table->smallInteger('notify_hr_with_application')->nullable();
            $table->smallInteger('preferred_communication_method')->nullable();
            $table->smallInteger('notify_employee_about_applications_submitted_on_their_behalf')->nullable();
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
        Schema::dropIfExists('leave_configuration');
    }
}
