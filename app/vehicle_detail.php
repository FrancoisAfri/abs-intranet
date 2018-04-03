<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vehicle_detail extends Model
{
    protected $table = 'vehicle_details';

    protected $fillable = ['name', 'description', 'status', 'responsible_for_maintenance', 'vehicle_make',
        'vehicle_model', 'vehicle_type', 'year', 'vehicle_registration', 'chassis_number', 'engine_number', 'vehicle_color',
        'metre_reading_type', 'odometer_reading', 'hours_reading', 'fuel_type', 'size_of_fuel_tank', 'fleet_number',
        'cell_number', 'tracking_umber', 'vehicle_owner', 'title_type', 'financial_institution', 'company', 'extras',
        'image', 'registration_papers', 'property_type'];

    public function vehiclebooking() {
        return $this->hasMany(vehicle_booking::class, 'vehicle_id');
    }

    public function vehiclefines() {
        return $this->hasMany(vehicle_fines::class, 'vehicleID');
    }
	
	 public function vehiclefuelLog() {
        return $this->hasMany(fuellogVehicle::class, 'vehicle_id');
    }

     public function vehicleDocs() {
        return $this->hasMany(vehicle_documets::class, 'vehicleID');
    }

     public function vehicleLicences() {
        return $this->hasMany(permits_licence::class, 'vehicleID');
    }

}
