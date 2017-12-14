<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vehicle_return_images extends Model
{
     protected $table = 'vehicle_return_images';

    protected $fillable = ['name', 'description', 'image', 'upload_date', 'user_name', 'status', 'vehicleID', 'bookingID'];
}
