<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FueltankTopUp extends Model
{
   public $table = 'fuel_tank_topUp';
    // Mass assignable fields
    protected $fillable = ['supplier_id','document_no','document_date' ,'topup_date','type'
	,'reading_before_filling','reading_after_filling','litres','cost_per_litre','total_cost'
	,'description','received_by','captured_by','status','tank_id','litre_used','make_or_model'
	,'registration_number','vehicle_fuel_id','vehicle_id','transaction_type','reject_reason'
	,'rejector_id','reject_timestamp','available_litres','litres_new'
    ];
}
