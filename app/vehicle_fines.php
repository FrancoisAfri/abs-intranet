<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vehicle_fines extends Model
{
    protected $table = 'vehicle_fines';

    protected $fillable = [ 'date_captured','fine_type','fine_ref','date_of_fine','time_of_fine','amount',
        'reduced','additional_fee','location','speed','zone_speed','driver','magistrate_office',
        'court_date','paid_date','amount_paid','description','fine_status','document','document1','document2','vehicleID'];

    public function vehicledetails(){
        return $this->belongsTo(vehicle_detail::class, 'vehicleID');
    }
}
