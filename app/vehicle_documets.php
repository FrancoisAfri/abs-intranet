<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vehicle_documets extends Model
{
    protected $table = 'vehicle_documets';

    protected $fillable = ['name', 'description', 'document', 'upload_date', 'user_name', 'status'
	, 'vehicleID', 'default_documrnt', 'exp_date', 'date_from', 'type', 'role' , 'expiry_type','currentdate'];

     public function vehicledocumets(){
        return $this->belongsTo(vehicledocumets::class, 'vehicleID');
    }
	public function documentType() {
        return $this->belongsTo(fleet_documentType::class, 'type');
    }
}
