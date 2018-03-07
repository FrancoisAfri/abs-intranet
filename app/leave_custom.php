<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class leave_custom extends Model
{
    protected $table = 'leave_customs';

    protected $fillable = [
       'hr_id','number_of_days','status'
    ];
    //
    public function userCustom() {
        return $this->belongsTo(HRPerson::class, 'hr_id');
    }
}
