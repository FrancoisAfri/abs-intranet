<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FueltankPrivateUse extends Model
{
    public $table = 'fuel_tank_privateUsage';
    // Mass assignable fields
    protected $fillable = ['document_no','document_date' ,'usage_date','type','make_or_model',
    'registration_number','description','received_by','captured_by','status','person_responsible'
	,'tank_id'
    ];
}
