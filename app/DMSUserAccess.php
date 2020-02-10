<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DMSUserAccess extends Model
{
        //Specify the table name
    public $table = 'd_m_s_user_accesses';

    //Mass assignable fields
    protected $fillable = [
        'hr_id', 'status', 'expiry_date', 'date_requested', 'date_approved', 'date_rejected'
		, 'date_modified', 'file_id', 'folder_id', 'admin_id'
    ];
	
	//relationship between folders and files
    public function employee()
    {
        return $this->belongsTo(HRPerson::class, 'hr_id');
    }	
	//relationship between folders and files
    public function userAdmin()
    {
        return $this->belongsTo(HRPerson::class, 'admin_id');
    }	
	//relationship between folders and files
    public function userFolder()
    {
        return $this->belongsTo(DmsFolders::class, 'folder_id');
    }	
	//relationship between folders and files
    public function userFile()
    {
        return $this->belongsTo(DmsFiles::class, 'file_id');
    }
}