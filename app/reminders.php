<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class reminders extends Model
{
    protected $table = 'vehicle_reminders';

    protected $fillable = [ 'name', 'description','status','start_date','end_date','vehicleID'];

}
