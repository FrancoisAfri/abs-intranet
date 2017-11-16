<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class fleet_licence_permit extends Model
{
    protected $table = 'fleet_licence_permit';

    protected $fillable = ['name', 'description', 'status'];

}
