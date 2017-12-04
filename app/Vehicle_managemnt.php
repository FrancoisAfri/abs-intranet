<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle_managemnt extends Model
{
    protected $table = 'vehicle_managemnet';

    protected $fillable = ['name', 'description', 'status'];
}
