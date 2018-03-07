<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class appraisalsKpis extends Model
{
    //Specify the table name
    public $table = 'appraisals_kpis';
	
	// Mass assignable fields
    protected $fillable = [
        'measurement', 'status', 'weight', 'source_of_evidence',
		'indicator', 'kpi_type', 'template_id', 'kpa_id',
		'category_id','is_upload','upload_type','existing_kpi_id', 'is_task_kpi', 'kpi_task_type'
    ];
		
	//Relationship template and Kpis
    public function kpiTemplate() {
		return $this->belongsTo(appraisalTemplates::class, 'template_id');
    }
	//Relationship categories and Kpis
    public function kpiscategory() {
		return $this->belongsTo(appraisalCategories::class, 'category_id');
    }
	//Relationship kpis and Kpas
    public function kpiskpas() {
		return $this->belongsTo(appraisalKpas::class, 'kpa_id');
    }
		//Relationship kpi and range
    public function kpiranges() {
		return $this->hasMany(appraisalsKpiRange::class, 'kpi_id');
    }
    //Relationship kpi and number
    public function kpiNumber() {
        return $this->hasMany(appraisalsKpiNumber::class, 'kpi_id');
    }
    //Relationship kpi and query report
    public function kpiQueryResults() {
        return $this->hasMany(AppraisalQuery_report::class, 'kpi_id');
    }
	//Relationship kpi and clock in
    public function kpiIntAttendanceResults() {
        return $this->hasMany(AppraisalClockinResults::class, 'kpi_id');
    }
	//Relationship kpi and integer score range
    public function kpiIntScore() {
        return $this->hasMany(AppraisalKPIIntRange::class, 'kpi_id');
    }
    /*//Relationship kpi and score type. dynamically returns the correct score type based on the kpi type
    public function kpiScoreType() {
        if ($this->kpi_type) return $this->kpiIntScore();
    }*/
    //Relationship kpi and result (Appraised by manager)
    public function results() {
        return $this->hasMany(AppraisalKPIResult::class, 'kpi_id');
    }

    //Relationship kpi and result (Appraised by self)
    public function empResults() {
        return $this->hasMany(AppraisalEmpKPIResult::class, 'kpi_id');
    }

    //Function to add a new kpi integer range
    public function addKPIIntRange(AppraisalKPIIntRange $kpiIntRange) {
        return $this->kpiIntScore()->save($kpiIntRange);
    }

    //Function to add a new kpi result
    public function addResult(AppraisalKPIResult $result) {
        return $this->kpiIntScore()->save($result);
    }
}
