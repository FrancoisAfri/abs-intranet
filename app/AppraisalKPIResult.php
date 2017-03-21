<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppraisalKPIResult extends Model
{
    //Specify the table name
    public $table = 'appraisal_k_p_i_results';

    // Mass assignable fields
    protected $fillable = ['result', 'date_uploaded', 'comment', 'hr_id', 'kip_id', 'template_id'];
}
