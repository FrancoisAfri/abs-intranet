<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class servicetype extends Model
{
    protected $table = 'service_type';

    protected $fillable = ['name', 'description', 'status'];
}
