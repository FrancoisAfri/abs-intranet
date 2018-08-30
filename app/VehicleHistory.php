<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleHistory extends Model
{
    //Specify the table name
    public $table = 'vehicle_histories';

    // Mass assignable fields
    protected $fillable = [
        'vehicle_id', 'status', 'comment', 'action_date', 'user_id'
    ];
	public function userName() {
		
		return $this->belongsTo(HRPerson::class, 'user_id');
    }
}
