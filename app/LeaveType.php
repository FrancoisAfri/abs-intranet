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
        //return $this->belongsToMany('App\leave_profile');
        return $this->belongsToMany('App\leave_profile', 'type_profile' ,'leave_type_id','leave_profile_id')->withPivot('max', 'min');
        
    }
}
