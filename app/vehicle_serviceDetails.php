<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vehicle_serviceDetails extends Model
{
	 protected $table = 'vehicle_serviceDetails';

    protected $fillable = [  'date_serviced','garage','invoice_number','total_cost','nxt_service_km',
							'description','document','document1','document2','nxt_service_date','vehicleID'];
}
