<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HRRoles extends Model
{
    //Specify the table name
    public $table = 'hr_roles';
	
	// Mass assignable fields
    protected $fillable = [
        'description','status',
    ];
}
