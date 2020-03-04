<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DMSGoupAdminUsers extends Model
{
    //Specify the table name
    public $table = 'd_m_s_goup_admin_users';

    //Mass assignable fields
    protected $fillable = [
        'hr_id', 'division_level_5', 'division_level_4'
		, 'status', 'group_id'
    ];
	
	//relationship between folders and files
    public function groupUser()
    {
        return $this->belongsTo(DMSGoupAdmin::class, 'group_id');
    }
	//relationship between folders and files
    public function users()
    {
        return $this->belongsTo(HRPerson::class, 'hr_id');
    }
}
