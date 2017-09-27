<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeetingAttendees extends Model
{
    //Specify the table name
    public $table = 'meeting_attendees';
	
	// Mass assignable fields
    protected $fillable = [
        'employee_id', 'attendance', 'apology', 'client_id', 'meeting_id'];
		
	//Relationship categories and Kpas
    public function meetings() {
        return $this->belongsTo(MeetingMinutes::class, 'meeting_id');
    }   
    //Relationship categories and Kpas
    public function attendeesInfo() {
        return $this->belongsTo(HRPerson::class, 'employee_id');
    }	
	//Relationship MeetingAttendee and 
    public function client() {
		return $this->belongsTo(ContactPerson::class, 'client_id');
    }
}
