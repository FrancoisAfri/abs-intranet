<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class appraisalTemplates extends Model
{
     //Specify the table name
    public $table = 'appraisal_templates';
	
	// Mass assignable fields
    protected $fillable = [
        'template', 'status', 'job_title_id'];
		
		//Relationship educator and user
    public function jobTitle() {
        return $this->belongsTo(JobTitle::class, 'job_title_id');
    }
}
