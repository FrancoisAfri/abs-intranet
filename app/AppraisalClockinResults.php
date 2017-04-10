<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppraisalClockinResults extends Model
{
   //Specify the table name
    public $table = 'appraisal_clockin_results';

    // Mass assignable fields
    protected $fillable = ['attendance', 'date_uploaded', 'hr_id', 'kip_id'];
	
            
    //Relationship kpi and IntegerRange
    public function kpiAttendance() {
        return $this->belongsTo(appraisalsKpis::class, 'kpi_id');
    }
}
