<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DMSCompanyAccess extends Model
{
        //Specify the table name
    public $table = 'd_m_s_company_accesses';

    //Mass assignable fields
    protected $fillable = [
        'hr_id', 'status', 'division_level_1', 'division_level_2', 'division_level_3'
		, 'division_level_4', 'division_level_5', 'folder_id', 'file_id', 'admin_id', 'expiry_date'
    ];
	
	//relationship between folders and files
    public function division()
    {
        return $this->belongsTo(DivisionLevelFive::class, 'division_level_5');
    }	
	//relationship between folders and files
    public function department()
    {
        return $this->belongsTo(DivisionLevelFour::class, 'division_level_4');
    }	//relationship between folders and files
    public function section()
    {
        return $this->belongsTo(DivisionLevelThree::class, 'division_level_3');
    }	//relationship between folders and files
    public function team()
    {
        return $this->belongsTo(DivisionLevelTwo::class, 'division_level_2');
    }	//relationship between folders and files
    public function levelOne()
    {
        return $this->belongsTo(DivisionLevelOne::class, 'division_level_1');
    }	
	//relationship between folders and files
    public function companyEmployee()
    {
        return $this->belongsTo(HRPerson::class, 'hr_id');
    }	
	//relationship between folders and files
    public function companyAdmin()
    {
        return $this->belongsTo(HRPerson::class, 'admin_id');
    }	
	//relationship between folders and files
    public function companyFolder()
    {
        return $this->belongsTo(DmsFolders::class, 'folder_id');
    }	
	//relationship between folders and files
    public function companyFile()
    {
        return $this->belongsTo(DmsFiles::class, 'file_id');
    }
}
