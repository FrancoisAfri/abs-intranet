<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DmsFolders extends Model
{
     //Specify the table name
    public $table = 'dms_folders';

    //Mass assignable fields
    protected $fillable = [
        'parent_id', 'max_size', 'responsable_person'
		, 'status', 'deleted'
		,'inherit_rights','folder_name'
    ];
	
	//relationship between folders and files
    public function filesList()
    {
        return $this->hasMany(DmsFiles::class, 'folder_id');
    }
}
