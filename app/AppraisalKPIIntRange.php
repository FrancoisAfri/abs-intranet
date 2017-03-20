<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppraisalKPIIntRange extends Model
{
    //Specify the table name
    public $table = 'appraisal_k_p_i_int_ranges';

    // Mass assignable fields
    protected $fillable = ['score', 'percentage'];

    //Relationship kpi and IntegerRange
    public function kpi() {
        return $this->belongsTo(appraisalsKpis::class, 'kpi_id');
    }
}
