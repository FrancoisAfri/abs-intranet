<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class jobcard_maintanance extends Model
{
    public $table = 'jobcard_maintanance';
    protected $fillable = ['vehicle_id', 'card_date', 'schedule_date', 'completion_date',
        'booking_date', 'supplier_id', 'service_type',
        'estimated_hours', 'service_file_upload', 'service_time',
        'machine_hour_metre', 'machine_odometer', 'last_driver_id',
        'mechanic_id','status', 'jobcard_number', 'date_default'
		, 'user_id', 'status_display', 'mechanic_comment', 'completion_comment'];
		
	 //Relationship JC and JC instructions
    public function JCinstructions() {
        return $this->hasMany(JobCardInstructions::class, 'job_card_id');
    }
	
	public function jobCardHistory() {
		
		return $this->hasMany(JobCardHistory::class, 'job_card_id');
    }
	//Relationship JC and Notes
    public function jcNotes() {
        return $this->hasmany(jobcardnote::class, 'jobcard_id');
    }
}
