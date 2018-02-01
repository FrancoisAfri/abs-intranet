<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vehicle_warranties extends Model
{
    protected $table = 'vehicle_warranties';

    protected $fillable = ['name', 'description', 'status', 'service_provider', 'contact_person', 'contact_number',
        'contact_email', 'address', 'policy_no', 'inception_date', 'exp_date', 'warranty_period', 'kilometers',
        'type', 'warranty_amount', 'notes', 'document','vehicleID'];
}
