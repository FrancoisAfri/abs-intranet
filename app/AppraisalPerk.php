<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppraisalPerk extends Model
{
    //Specify the table name
    public $table = 'appraisal_perks';

    // Mass assignable fields
    protected $fillable = [
        'name', 'description', 'req_percent'
    ];
}
