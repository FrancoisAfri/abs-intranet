<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class leave_history extends Model
{
    //
    protected $table = 'leave_history';
    protected $fillable = ['hr_id','performed_by','description_action','previous_balance','transcation'];

    // #types of leave profiles belonging to leave types
     public function leavehistory() {
        return $this->hasMany(HRPerson::class, 'hr_id');
    }

}
