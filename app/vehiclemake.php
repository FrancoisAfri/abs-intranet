<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vehiclemake extends Model
{
    protected $table = 'vehicle_make';

    protected $fillable = ['name', 'description', 'status'];

    public function vehieclemake_model() {
        return $this->hasMany(vehiclemodel::class, 'make_id');
    }

    
}
