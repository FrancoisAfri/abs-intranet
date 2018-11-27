<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock_Approvals_level extends Model
{
    protected $table = 'stock__approvals_levels';

    protected $fillable = ['stock_level_5', 'stock_level_4', 'stock_level_3', 'stock_level_2',
        'stock_level_1', 'step_name', 'step_number', 'max_amount', 'job_title', 'status'
		,'date_added'];
	
	//relationship division level details and each specific division level(one to many)
    public function divisionLevelFive() {
         return $this->belongsTo(stockLevelFive::class, 'stock_level_5');
        
    }
	//relationship division level details and each specific division level(one to many)
    public function divisionLevelFour() {
         return $this->belongsTo(stockLevelFour::class, 'stock_level_4');
        
    }
	//relationship division level details and each specific division level(one to many)
    public function divisionLevelThree() {
         return $this->belongsTo(stockLevelThree::class, 'stock_level_3');
        
    }
	//relationship division level details and each specific division level(one to many)
    public function divisionLevelTwo() {
         return $this->belongsTo(stockLevelTwo::class, 'stock_level_2');
        
    }
	//relationship division level details and each specific division level(one to many)
    public function divisionLevelOne() {
         return $this->belongsTo(stockLevelOne::class, 'stock_level_1');
        
    }

}
