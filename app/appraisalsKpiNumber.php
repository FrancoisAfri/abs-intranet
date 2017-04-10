<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class appraisalsKpiNumber extends Model
{
         //Specify the table name
    public $table = 'appraisals_kpi_numbers';
	
	// Mass assignable fields
    protected $fillable = [
        'min_number', 'max_number','kpi_id','status'];
		
	//Relationship kpi and range
    public function kpiNumber() {
		return $this->belongsTo(appraisalsKpis::class, 'kpi_id');
    }
}
