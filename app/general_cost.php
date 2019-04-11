<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class general_cost extends Model
{
    //
    protected $table = 'vehicle_generalcosts';

    protected $fillable = ['date', 'document_number', 'supplier_name'
	,'cost_type', 'cost', 'litres', 'litres_new'
	,'description', 'person_esponsible', 'vehicleID'];
}