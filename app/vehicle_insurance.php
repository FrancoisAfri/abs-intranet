<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vehicle_insurance extends Model
{
     protected $table = 'vehicle_insurance';

    protected $fillable = ['name', 'description', 'status','registration' ,'service_provider', 'contact_person', 'contact_number',
        'contact_email', 'address', 'policy_no', 'inception_date','value_coverd', 'policy_type',
        'type', 'premium_amount', 'notes', 'document','vehicleID'];
}
