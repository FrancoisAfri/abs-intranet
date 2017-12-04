<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FleetType extends Model
{
    protected $table = 'fleet_type';

    protected $fillable = ['name', 'description', 'status'];
}
