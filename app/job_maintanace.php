<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class job_maintanace extends Model
{
    protected $table = 'job_maintanace';

    protected $fillable = ['vehicle', 'service_days', 'job_card_date', 'schedule_date', 'booking_date',
        'supplier', 'service_type', 'estimated_hours', 'service_docs', 'service_time',
        'machine_hour_metre', 'machine_odometer', 'last_driver', 'inspection_info',
        'inspection_docs', 'mechanic', 'emails', 'instruction'];
}
