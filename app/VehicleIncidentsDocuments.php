<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleIncidentsDocuments extends Model
{
   protected $table = 'vehicle_incident_documents';	
   protected $fillable = ['incident_id', 'filename', 'display_name', 'status'];
}
