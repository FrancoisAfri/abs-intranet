<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tank extends Model
{
   protected $table = 'tank';

    protected $fillable = ['name', 'description', 'status'];
}
