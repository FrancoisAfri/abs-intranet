<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppraisalSetup extends Model
{
    //Specify the table name
    public $table = 'appraisal_setup';

    // Mass assignable fields
    protected $fillable = [
        'manager_appraisal_weight', 'employee_appraisal_weight', 'colleagues_appraisal_weight', 'three_sixty_status'
    ];
}