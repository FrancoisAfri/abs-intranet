<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobTitle extends Model
{
    protected $table = 'hr_positions';

    protected $fillable = [
        'name', 'description', 'status', 'category_id'
    ];

    //Relationship Categories and job title
    public function jobTitleCat()
    {
        return $this->belongsTo(JobCategory::class, 'category_id');
    }

    //Relationship job title and hr person
    public function hrPerson()
    {
        return $this->hasMany(HRPerson::class, 'position');
    }

    //Relationship appraisal job title and template
    public function kpiTemplate()
    {
        return $this->hasOne(appraisalTemplates::class, 'job_title_id');
    }
}
