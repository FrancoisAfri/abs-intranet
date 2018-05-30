<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{
    protected $table = 'job_categories';

    protected $fillable = [
        'name', 'description', 'status'
    ];

    //Relationship Categories and jobtitle
    public function catJobTitle()
    {
        return $this->hasmany(JobTitle::class, 'category_id');
    }
}
