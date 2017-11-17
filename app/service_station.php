<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class service_station extends Model
{
    protected $table = 'service_station';

    protected $fillable = ['name', 'description', 'status'];
    
}
