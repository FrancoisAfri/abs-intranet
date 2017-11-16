<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class fleet_fillingstation extends Model
{
    protected $table = 'fleet_fillingstation';

    protected $fillable = ['name', 'description', 'status'];
}
