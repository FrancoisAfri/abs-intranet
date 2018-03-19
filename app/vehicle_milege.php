<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vehicle_milege extends Model
{
    protected $table = 'vehicle_mileage';

    protected $fillable = ['date_created', 'date_taken', 'vehicle_id', 'odometer_reading', 'type', 'booking_id', 'hours_reading'];
}
