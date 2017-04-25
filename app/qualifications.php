<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class qualifications extends Model
{
      protected $table = 'qualification';

    protected $fillable = [
       'Institution','Qualification','Year Obtained', 'Qualification Type', 'Certificate', 'status'
    ];
}
 