<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vehicle_fuel_log extends Model
{

	protected $table = 'vehicle_fuel_log';

    protected $fillable = ['driver', 'document_number', 'date','tank_type','tank_name',
								'Hoursreading' ,'description', 'captured_by','vehicleID',
								'service_station','transaction_type','cost_per_litre'
								,'total_cost','tank_and_other','status'
								,'reject_reason','reject_timestamp','rejector_id'
								,'published_at','litres_new'
								,'actual_km_reading','actual_hr_reading'
								,'Odometer_reading','Hoursreading'];

	public function fuellogVehicle() {
			return $this->belongsTo(vehicle_detail::class, 'vehicle_id');
		}
}