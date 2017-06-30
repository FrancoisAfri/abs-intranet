<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Qualification_type extends Model
{
    //
     protected $table = 'Qualification_type';

    protected $fillable = [
       'description','name','status'
    ];
}
