<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppraisalSurvey extends Model
{
    //Specify the table name
    public $table = 'appraisal_surveys';
    
    // Mass assignable fields
    protected $fillable = [
        'feedback_date', 'hr_person_id', 'client_name', 'booking_number', 'attitude_enthusiasm', 'expertise',
        'efficiency', 'attentive_listening', 'general_overall_assistance', 'additional_comments'
    ];
}
