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

    /**
     * Accessor function to return a survey's average rating.
     *
     * @return double $avgRating
     */
    public function getAvgRatingAttribute() {
        return $this->surveyQuestions->avg('pivot.result');
    }

    /**
     * Relationship with SurveyQuestions [Many to Many].
     *
     * @return
     */
    public function surveyQuestions() {
        return $this->belongsToMany(SurveyQuestions::class, 'appraisal_survey_questions', 'survey_id', 'question_id')
            ->withPivot('result')
            ->withTimestamps();
    }
}
