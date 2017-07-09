<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecurringMeetings extends Model
{
    //Specify the table name
    public $table = 'recurring_meetings';
	
	// Mass assignable fields
    protected $fillable = [
        'meeting_title', 'meeting_date', 'meeting_location', 'meeting_agenda', 'status'];
		
	//Relationship categories and Kpas
    public function recurringAttendees() {
        return $this->hasmany(RecurringMeetingsAttendees::class, 'meeting_id');
    }
	//Function to save recurring meeting employees
   /* public function addEmployees(array $areaIDs) {
        $areas = [];
        foreach ($areaIDs as $areaID) {
            $areas[] = new RegistrationArea(['area_id' => $areaID]);
        }
        return $this->subjects()->saveMany($areas);
    }*/
}
