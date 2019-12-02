<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class type_profile extends Model
{
    //
     protected $table = 'type_profile';
    
    protected $fillable = ['min','max','leave_type_id','leave_profile_id'];   
}