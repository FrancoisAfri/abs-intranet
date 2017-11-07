<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vehicle_maintenance extends Model
{
    protected $table = 'vehicle_maintenance';

    protected $fillable = ['name', 'description', 'status', 'vehicle_make', 'vehicle_model',
        'vehicle_type', 'year', 'vehicle_registration', 'chassis_number', 'engine_number',
        'vehicle_color', 'metre_reading_type', 'odometer_reading', 'hours_reading', 'fuel_type',
        'size_of_fuel_tank', 'fleet_number', 'cell_number', 'tracking_umber', 'vehicle_owner',
        'title_type', 'financial_institution', 'company', 'extras', 'image', 'property_type'];

        
         //Define image - vehicle_maintenance relationship
         public function image() {
        return $this->hasMany(images::class, 'vehicle_maintanace');
    }
}
