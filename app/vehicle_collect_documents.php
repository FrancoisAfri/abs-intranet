<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vehicle_collect_documents extends Model
{
    protected $table = 'vehicle_collect_documents';

    protected $fillable = ['type', 'description', 'document', 'upload_date', 'user_name', 'status', 'vehicleID', 'bookingID'];
 
}
