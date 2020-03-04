<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class leave_application extends Model
{
       protected $table = 'leave_application';
    protected $fillable = ['notes','supporting_docs','start_date',
                          'end_date','status','hr_id','leave_type_id','start_time',
                          'end_time','manager_id','reject_reason','leave_days','leave_hours'
						  ,'leave_taken'
                          ];
    // public $status = [
    // 0 => 'Pending', 
    // 1 => 'require_managers_approval',
    // 2 => 'require_department_head_approval',
    // 3 => 'require_hr_approval',
    // 4 => 'require_payroll_approval',
    // 5 => 'Approved', 5 => 'Rejected'
    // ];
    //->status
    //->leave_status
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
        return $this->belongsTo(HRPerson::class, 'hr_id');
    }

    //Relationship leave_application and canceller (hr people)
    public function canceller() {
        return $this->belongsTo(HRPerson::class, 'canceller_id');
    }
    // //Return leave status string valu
    // public function getLeaveStatusAttribute () {
    //   return $status[$this->status];
    // }
}
