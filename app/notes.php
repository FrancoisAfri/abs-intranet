<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class notes extends Model
{

    public $table = 'notes';
    protected $fillable = ['date_captured', 'captured_by', 'notes', 'documents', 'vehicleID'];

}
