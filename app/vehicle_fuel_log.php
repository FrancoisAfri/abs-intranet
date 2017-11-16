<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vehicle_fuel_log extends Model
{

	 protected $table = 'vehicle_fuel_log';

    protected $fillable = ['driver', 'document_number', 'date','tank_type','tank_name','litres',
								'hours_reading' ,'description', 'captured_by','vehicleID', 'rensonsible_person'];
    
}
