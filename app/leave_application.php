<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class leave_application extends Model
{
       protected $table = 'leave_application';
    protected $fillable = ['application','notes','supporting_docs','start_date',
                          'end_date','status','hr_id','leave_type_id','start_time',
                          'end_time','manager_id'
                          ];
                          
    
    

    
    //Relationship leave_application and leave_type
    public function leavetpe(){
       return $this->belongsTo(LeaveType::class, 'leave_type_id'); 
    }
    
    //Relationship leave_application and hr people
    public function manager() {
        return $this->hasMany(HRPerson::class, 'manager_id');
    }
    
    //Relationship leave_application and hr people
    public function person() {
        return $this->hasMany(HRPerson::class, 'hr_id');
    }
}
