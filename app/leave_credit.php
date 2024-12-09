<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class leave_credit extends Model
{
    protected $table = 'leave_credit';
    protected $fillable = ['hr_id', 'leave_balance', 'leave_type_id'];
	//Relationship leave_application and hr people
    public function person()
    {
        return $this->belongsTo(HRPerson::class, 'hr_id');
    }
    public static function getLeaveCredit($hrID, $typID)
    {
      return  leave_credit::where(
            [
                'hr_id' => $hrID,
                'leave_type_id' => $typID
            ]
        )->first();
    }
	
}