<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class safe extends Model
{
    protected $table = 'safe';

    protected $fillable = ['name', 'description', 'status'];
}
