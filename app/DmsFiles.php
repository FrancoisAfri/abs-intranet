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
		,'inherit_rights','file_name','current_version','description'
		,'document_name','path','file_extension'
    ];
	
	//relationship between folders and files
    public function folderList()
    {
        return $this->belongsTo(DmsFolders::class, 'folder_id');
    }
	
	//relationship between files and files versions
    public function fileVersions()
    {
        return $this->hasMany(DmsFilesVersions::class, 'file_id');
    }
		//relationship between files and responsable_person
    public function employee()
    {
        return $this->belongsTo(HRPerson::class, 'responsable_person');
    }
}