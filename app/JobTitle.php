<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobTitle extends Model
{
    protected $table = 'hr_positions';
	
    	 protected $fillable = [
        'name', 'description', 'status', 'category_id'
    ];
	
	 //Relationship Categories and jobtitle
    public function jobTitleCat() {
        return $this->belongsTo(JobCategory::class, 'category_id');
    }
}
