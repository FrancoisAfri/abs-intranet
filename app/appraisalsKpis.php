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
		'category_id'];
		
	//Relationship template and Kpis
    public function kpistemplate() {
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
}