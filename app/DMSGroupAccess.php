<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DMSGroupAccess extends Model
{
        //Specify the table name
    public $table = 'd_m_s_group_accesses';

    //Mass assignable fields
    protected $fillable = [
        'admin_id', 'status', 'hr_id', 'file_id', 'folder_id', 'group_id', 'expiry_date'
    ];
	
	//relationship between folders and files
    public function groupName()
    {
        return $this->belongsTo(DMSGoupAdmin::class, 'group_id');
    }
		//relationship between folders and files
    public function groupAdmin()
    {
        return $this->belongsTo(HRPerson::class, 'admin_id');
    }
		//relationship between folders and files
    public function groupFolder()
    {
        return $this->belongsTo(DmsFolders::class, 'folder_id');
    }	
	//relationship between folders and files
    public function groupFile()
    {
        return $this->belongsTo(DmsFiles::class, 'file_id');
    }
}
