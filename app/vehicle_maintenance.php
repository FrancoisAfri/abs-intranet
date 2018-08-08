<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vehicle_maintenance extends Model
{
    protected $table = 'vehicle_details';

    protected $fillable = ['name', 'description', 'status', 'responsible_for_maintenance', 'vehicle_make', 'vehicle_model',
        'vehicle_type', 'year', 'vehicle_registration', 'chassis_number', 'engine_number',
        'vehicle_color', 'metre_reading_type', 'odometer_reading', 'hours_reading', 'fuel_type',
        'size_of_fuel_tank', 'fleet_number', 'cell_number', 'tracking_umber', 'vehicle_owner',
        'title_type', 'financial_institution', 'company', 'extras', 'image', 'property_type','rejector_id',
        'division_level_1', 'division_level_2', 'division_level_3', 'division_level_4', 'division_level_5','reject_reason'
		,'reject_timestamp','author_id'];

    public function images()
    {
        return $this->hasMany(images::class, 'vehicle_maintanace');
    }

}

