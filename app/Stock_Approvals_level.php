<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock_Approvals_level extends Model
{
    protected $table = 'stock_approvals_levels';

    protected $fillable = ['division_level_5', 'division_level_4', 'division_level_3'
	, 'division_level_2', 'division_level_1', 'employee_id', 'step_name', 'step_number'
	, 'max_amount', 'job_title', 'status' ,'date_added'];
	
	//relationship division level details and each specific division level(one to many)
    public function divisionLevelFive() {
         return $this->belongsTo(DivisionLevelFive::class, 'division_level_5');
        
    }
	//relationship division level details and each specific division level(one to many)
    public function divisionLevelFour() {
         return $this->belongsTo(DivisionLevelFour::class, 'division_level_4');
        
    }
	//relationship division level details and each specific division level(one to many)
    public function divisionLevelThree() {
         return $this->belongsTo(DivisionLevelThree::class, 'division_level_3');
        
    }
	//relationship division level details and each specific division level(one to many)
    public function divisionLevelTwo() {
         return $this->belongsTo(DivisionLevelTwo::class, 'division_level_2');
        
    }
	//relationship division level details and each specific division level(one to many)
    public function divisionLevelOne() {
         return $this->belongsTo(DivisionLevelOne::class, 'division_level_1');
        
    }
	//relationship division level details and each specific division level(one to many)
    public function employeeDetails() {
         return $this->belongsTo(HRPerson::class, 'employee_id');
        
    }

}
