<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveAllocation extends Model
{
    protected $table = 'leave_allocation';

    protected $fillable = [
       'hr_id','month_allocated','leave_type_id','allocated_by'
	   ,'date_allocated','balance_before','current_balance'
    ];
    //
    public function employees() {
        return $this->belongsTo(HRPerson::class, 'hr_id');
    }
}
