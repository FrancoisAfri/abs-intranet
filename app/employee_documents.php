<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class employee_documents extends Model
{
    //
     protected $table = 'employee_documents';

    protected $fillable = ['category_id','doc_description','division_level_2','division_level_1','hr_person_id','expirydate','employeedocs'];
}
