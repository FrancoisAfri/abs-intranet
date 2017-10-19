<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vehiclemodel extends Model
{
   protected $table = 'vehicle_model';

    protected $fillable = [ 'name', 'description','status'];
}
