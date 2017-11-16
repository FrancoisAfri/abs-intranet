<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vehiclemake extends Model
{
    protected $table = 'vehicle_make';

    protected $fillable = ['name', 'description', 'status'];
}
