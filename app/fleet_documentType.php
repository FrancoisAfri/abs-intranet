<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class fleet_documentType extends Model
{
    protected $table = 'fleet_documentType';

    protected $fillable = ['name', 'description', 'status'];

}
