<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class fleetcard_type extends Model
{
     protected $table = 'card_type';

    protected $fillable = ['name', 'description', 'status'];
}
