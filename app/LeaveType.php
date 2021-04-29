<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    //
    protected $table = 'leave_types';
    protected $fillable = ['name','status','description'];
    // #types of leave profiles belonging to leave types
    public function leave_profle()
    {
        //Many to many Relationship Between leavetype and leave_profile
        return $this->belongsToMany('App\leave_profile', 'type_profile' ,'leave_type_id','leave_profile_id')->withPivot('max', 'min');
    }
    public function hr_person()
    {
        ////Many to many Relationship Between leavetype and Hr person
        return $this->belongsToMany('App\HRPerson', 'leave_credit' ,'leave_type_id','hr_id')->withPivot('leave_balance');
            
    }
    public function leaveApp() {
        return $this->hasmany(leave_application::class, 'leave_type_id');
    }
}
