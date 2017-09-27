<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeetingMinutes extends Model
{
    //Specify the table name
    public $table = 'meeting_minutes';
	
	// Mass assignable fields
    protected $fillable = [
        'meeting_name', 'meeting_date', 'meeting_location', 'meeting_agenda', 'minutes', 'company_id'];
		
	//Relationship categories and Kpas
    public function attendees() {
        return $this->hasmany(MeetingAttendees::class, 'meeting_id');
    }
	//Relationship categories and Kpas
    public function tasksMeeting() {
        return $this->hasmany(EmployeeTasks::class, 'meeting_id');
    }
    //Relationship meeting and minutes
    public function MinutesMeet() {
        return $this->hasmany(MeetingsMinutes::class, 'meeting_id');
    }
	//Relationship meeting and ContactCompany
    public function company() {
        return $this->belongsTo(ContactCompany::class, 'company_id');
    }
}
