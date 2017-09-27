<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeetingsMinutes extends Model
{
    //Specify the table name
    public $table = 'meetings_minutes';
	
	// Mass assignable fields
    protected $fillable = [
        'meeting_id', 'employee_id', 'minutes', 'client_id'];
	   
    //Relationship minutes and person
    public function minutesPerson() {

        return $this->belongsTo(HRPerson::class, 'employee_id');
    }	
	//Relationship minutes and client
    public function client() {
		return $this->belongsTo(ContactPerson::class, 'client_id');
    }
	//Relationship meeting and minutes
    public function minutesMeeting() {

        return $this->belongsTo(MeetingMinutes::class, 'meeting_id');
    }
}