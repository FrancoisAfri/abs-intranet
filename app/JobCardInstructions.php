<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobCardInstructions extends Model
{
    protected $table = 'job_card_instructions';

    protected $fillable = ['job_card_id', 'instruction_details', 'status', 'completion_date', 'completion_time'];
}
