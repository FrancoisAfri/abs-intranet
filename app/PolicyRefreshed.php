<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PolicyRefreshed extends Model
{
     public $table = 'policy_refreshed';

    // Mass assignable fields
    protected $fillable = ['policy_id', 'hr_id',  'status', 'date_refreshed'
    ];

    public function policy()
    {
        return $this->belongsTo(Policy::class, 'policy_id');
    }
	//
	public function employees()
    {
        return $this->belongsTo(HRPerson::class, 'hr_id');
    }
}
