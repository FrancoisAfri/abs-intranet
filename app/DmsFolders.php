<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DmsFolders extends Model
{
    //Specify the table name
    public $table = 'dms_folders';

    //Mass assignable fields
    protected $fillable = [
        'parent_id', 'size', 'responsable_person'
		, 'status', 'deleted', 'visibility'
		,'inherit_rights','folder_name','division_5','division_4'
		,'division_3','division_2','division_1','path'
    ];
	
	//relationship between folders and files
    public function filesList()
    {
        return $this->hasMany(DmsFiles::class, 'folder_id');
    }
	//relationship between folders and responsable_person
    public function employee()
    {
        return $this->belongsTo(HRPerson::class, 'responsable_person');
    }
	//relationship between folders and division_5
    public function division()
    {
        return $this->belongsTo(DivisionLevelFive::class, 'division_5');
    }
	//relationship between folders and division_4
    public function department()
    {
        return $this->belongsTo(DivisionLevelFive::class, 'division_4');
    }
	//relationship between folders and division_3
    public function section()
    {
        return $this->belongsTo(DivisionLevelFive::class, 'division_3');
    }
	//relationship between folders and division_2
    public function team()
    {
        return $this->belongsTo(DivisionLevelFive::class, 'division_2');
    }
	//relationship between folders and parent
    public function parentDetails()
    {
        return $this->belongsTo(DmsFolders::class, 'parent_id');
    }
}