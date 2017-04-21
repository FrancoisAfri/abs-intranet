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
    public $description_action = [
        1 => 'Leave Adjusted By Admin',
        2 => 'Leave Adjusted By System ',
        3 => 'Adjusted By Manager',
        4 => 'Pending'

    ];

     public function geDescriptionActionStrValAttribute($intVal){
         return $this->description_action[$intVal];
     }
     //->description_action_str_val
     public function hrPerson() {
        return $this->hasMany(HRPerson::class, 'position');
    }
}
