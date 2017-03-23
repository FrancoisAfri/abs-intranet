<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class leave_credit extends Model
{
      protected $table = 'leave_credit';
    protected $fillable = ['hr_id','leave_balance','leave_type_id'];

    // #types of leave profiles belonging to leave types
//     public function hr_person() {
//        return $this->belongTo(HRPerson::class, 'hr_id');
//    }
}
