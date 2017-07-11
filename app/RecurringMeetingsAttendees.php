<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecurringMeetingsAttendees extends Model
{
    //Specify the table name
    public $table = 'recurring_meetings_attendees';
	
	// Mass assignable fields
    protected $fillable = [
        'employee_id','client_id', 'meeting_id', 'status'];
		
	//Relationship categories and Kpas
    public function recurringMeeting() {
        return $this->belongsTo(RecurringMeetings::class, 'meeting_id');
    }	
	//Relationship recurring meeting and employee_id
    public function attendeesDetails() {
		return $this->belongsTo(HRPerson::class, 'employee_id');
    }
}
