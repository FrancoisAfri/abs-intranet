<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class employee_documents extends Model
{
    //
    protected $table = 'employee_documents';

    protected $fillable = ['doc_description','category_id','division_level_2','division_level_1','hr_person_id','date_from','expirydate','supporting_docs','doc_type_id','qualification_type_id'];
}
