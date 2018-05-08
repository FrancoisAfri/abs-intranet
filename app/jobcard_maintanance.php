<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class jobcard_maintanance extends Model
{
    public $table = 'jobcard_maintanance';
    protected $fillable =  ['vehicle_id','card_date' ,'schedule_date',
							'booking_date','supplier' ,'service_type',
							'estimated_hours','service_file_upload' ,'service_time',
							'machine_hour_metre','machine_odometer','last_driver_id',
							'inspection_info','inspection_file_upload','mechanic_id',
							'instruction','status','jobcard_number','date_default','user_id'];
}
