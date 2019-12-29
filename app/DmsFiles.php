<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DmsFiles extends Model
{
     //Specify the table name
    public $table = 'dms_files';

    //Mass assignable fields
    protected $fillable = [
        'folder_id', 'max_size', 'responsable_person'
		, 'status', 'deleted', 'visibility'
		,'inherit_rights','file_name'
    ];
	
	//relationship between folders and files
    public function folderList()
    {
        return $this->belongsTo(DmsFolders::class, 'folder_id');
    }
}