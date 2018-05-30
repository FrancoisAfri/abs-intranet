<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class jobcardMaintance extends Model
{
    protected $table = 'jobcardMaintance';

    protected $fillable = ['name', 'description', 'status'];
}
