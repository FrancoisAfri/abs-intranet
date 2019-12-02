<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class leave_credit extends Model
{
    protected $table = 'leave_credit';
    protected $fillable = ['hr_id','leave_balance','leave_type_id'];
}