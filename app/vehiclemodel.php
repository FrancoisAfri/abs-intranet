<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vehiclemodel extends Model
{
    protected $table = 'vehicle_model';

    protected $fillable = ['name', 'description', 'status','vehiclemake_id'];

    public function vehiclemake() {
        return $this->belongsTo(vehiclemake::class, 'make_id');
    }
}
