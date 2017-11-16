<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class leave_configuration extends Model
{
    //
    protected $table = 'leave_configuration';

    protected $fillable = ['allow_annualLeave_credit',
'allow_sickLeave_credit',
'show_non_employees_in_leave_Module',
'require_managers_approval',
'all_managers_to_approve',
'require_department_head_approval',
'require_hr_approval',
'require_payroll_approval',
'limit_administration_to_assigned_divisions',
'number_of_days_until_escalation',
'document_compulsory_on_Study_leave_application',
'document_compulsory_when_two_sick_leave_8_weeks',
'notify_hr_with_application',
'preferred_communication_method',
'notify_employee_about_applications_submitted_on_their_behalf',
'annual_negative_days',
'sick_negative_days',
'number_of_days_annual',
'number_of_days_sick',
'allow_sick_negative_days',
'allow_annual_negative_days',
'mumber_of_days_until_escalation',
 ];
}
