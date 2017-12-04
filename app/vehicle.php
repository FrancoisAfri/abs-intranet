<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vehicle extends Model
{
    protected $table = 'vehicle';

    protected $fillable = ['name', 'description', 'status'];
}
