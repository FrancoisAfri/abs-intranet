<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vehicle_return_documents extends Model
{

   protected $table = 'vehicle_return_documents';	
   protected $fillable = ['type', 'description', 'document', 'upload_date', 'user_name', 'status', 'vehicleID', 'bookingID'];
}
