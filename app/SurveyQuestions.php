<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestions extends Model
{
    public $table = 'survey_questions';
    
    // Mass assignable fields
    protected $fillable = [
        'division_level_1', 'division_level_2', 'division_level_3', 'division_level_4'
		, 'division_level_5', 'status', 'description'];

    /**
     * Relationship with AppraisalSurvey [Many to Many].
     *
     * @return
     */
    public function appraisalSurvey() {
        return $this->belongsToMany(AppraisalSurvey::class);
    }
}