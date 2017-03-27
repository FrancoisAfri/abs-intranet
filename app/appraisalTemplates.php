<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class appraisalTemplates extends Model
{
     //Specify the table name
    public $table = 'appraisal_templates';
	
	// Mass assignable fields
    protected $fillable = [
        'template', 'status',
    ];
		
    //Relationship appraisal template and job title
    public function jobTitle() {
        return $this->belongsTo(JobTitle::class, 'job_title_id');
    }
	
    //Relationship template and Kpis
    public function kpi() {
        return $this->hasMany(appraisalsKpis::class, 'template_id');
    }
}
