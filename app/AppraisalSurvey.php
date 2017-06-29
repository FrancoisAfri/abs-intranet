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
     * Accessor function to return a surveys average rating.
     *
     * @return double $avgRating
     */
    public function getAvgRatingAttribute() {
    	$avgRating = null;
    	$totalRating = 0;
    	$ratingAreas = 0;
    	if ($this->attitude_enthusiasm) {
    		$totalRating += $this->attitude_enthusiasm;
    		$ratingAreas += 1;
    	}
    	if ($this->expertise) {
    		$totalRating += $this->expertise;
    		$ratingAreas += 1;
    	}
    	if ($this->efficiency) {
    		$totalRating += $this->efficiency;
    		$ratingAreas += 1;
    	}
    	if ($this->attentive_listening) {
    		$totalRating += $this->attentive_listening;
    		$ratingAreas += 1;
    	}
    	if ($this->attitude_enthusiasm) {
    		$totalRating += $this->general_overall_assistance;
    		$ratingAreas += 1;
    	}
    	$avgRating = ($ratingAreas > 0) ? $totalRating / $ratingAreas : null;

    	return $avgRating;
    }
}
