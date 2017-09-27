<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class leave_status extends Model
{
     public $table = 'leave_status';
     protected $fillable = ['name' ,'description'];
}
