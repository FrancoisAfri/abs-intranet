<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class nswStx_grade extends Model
{
    protected $table = 'nsw_stx_grades';
    
    //Mass assignable fields
    protected $fillable = [
        'nsw_id', 'learner_number', 'male_number', 'female_number', 'african_number', 'asian_number'
		, 'caucasian_number', 'coloured_number', 'indian_number', 'grade'
    ];
    //Relationship
    public function nswGrade() {
        return $this->belongsTo(nswStx::class, 'nsw_id');
    }
    
}
