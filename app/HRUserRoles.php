<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HRUserRoles extends Model
{
    //Specify the table name
    public $table = 'hr_users_roles';
	
	// Mass assignable fields
    protected $fillable = [
        'hr_id','role_id','status','date_allocated',
    ];
}