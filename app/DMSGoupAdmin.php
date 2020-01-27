<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DMSGoupAdmin extends Model
{
    //Specify the table name
    public $table = 'd_m_s_goup_admins';

    //Mass assignable fields
    protected $fillable = [
        'group_name', 'status', 'group_admin'
    ];
	
	//relationship between folders and files
    public function groupAmdin()
    {
        return $this->hasMany(HRPerson::class, 'group_admin');
    }	
	//relationship between folders and files
    public function groupUsers()
    {
        return $this->hasMany(DMSGoupAdminUsers::class, 'group_id');
    }
}
