<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DivisionLevel extends Model
{
    //Specify the table name
    public $table = 'division_setup';

    // Mass assignable fields
    protected $fillable = [
        'name', 'active'
    ];
}
