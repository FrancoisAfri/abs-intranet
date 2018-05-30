<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class processflow extends Model
{
    public $table = 'jobcard_process_flow';

    // Mass assignable fields
    protected $fillable = ['step_number', 'step_name', 'job_title', 'status'];
}
