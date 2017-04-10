<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class leave_profile extends Model
{
    //
    protected $table = 'leave_profile';
    protected $fillable = ['name','description'];

    public function leave_types()
    {
        return $this->belongsToMany('App\LeaveType','type_profile' ,'leave_type_id','leave_profile_id')->withPivot('max', 'min');
    }

}
