<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vehicle_collect_image extends Model
{
     protected $table = 'vehicle_collect_image';

    protected $fillable = ['name', 'description', 'image', 'upload_date', 'user_name', 'status', 'vehicleID', 'bookingID'];
}
