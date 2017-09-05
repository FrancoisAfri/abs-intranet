<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class qualifications extends Model
{
      protected $table = 'qualification';

    protected $fillable = ['division_level_2','division_level_1','hr_person_id','Institution',
        'Qualification','yearObtained', 'Qualification_Type','Certificate','Qualification',
        'status','Major','status','country','supporting_docs'];
}

