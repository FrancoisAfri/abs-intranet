<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DmsFilesVersions extends Model
{
    public $table = 'dms_files_versions';

    //Mass assignable fields
    protected $fillable = [
        'file_id', 'file_name', 'path'
		, 'status', 'version_number'
    ];
	
	//relationship between files and files versions
    public function versions()
    {
        return $this->belongsTo(DmsFiles::class, 'file_id');
    }
}
