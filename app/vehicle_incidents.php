<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vehicle_incidents extends Model
{

	 protected $table = 'vehicle_incidents';

    protected $fillable = ['date_of_incident','incident_type','severity','reported_by','odometer_reading','status','claim_number','description','Cost','vehicleID'];

}
