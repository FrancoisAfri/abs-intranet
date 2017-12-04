<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vehicle_oil_log extends Model
{
    protected $table = 'vehicle_oil_log';

    protected $fillable = ['name', 'description', 'status'];
}
