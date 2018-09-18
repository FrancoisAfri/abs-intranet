<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleCommunications extends Model
{
        //Specify the table name
    public $table = 'fleet_communications';

    // Mass assignable fields
    protected $fillable = [
        'communication_type', 'message', 'vehicle_id', 'status', 'sent_by', 'communication_date', 'time_sent'];
}
