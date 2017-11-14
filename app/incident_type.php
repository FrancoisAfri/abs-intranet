<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class incident_type extends Model
{
    protected $table = 'incident_type';

    protected $fillable = ['name', 'description', 'status'];
}
