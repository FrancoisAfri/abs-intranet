<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class appraisalsKpiRange extends Model
{
     //Specify the table name
    public $table = 'appraisals_kpi_ranges';
	
	// Mass assignable fields
    protected $fillable = [
        'range_from', 'range_to','percentage','kpi_id','status'];
		
	//Relationship kpi and range
    public function kpirange() {
		return $this->belongsTo(appraisalsKpis::class, 'kpi_id');
    }
}
