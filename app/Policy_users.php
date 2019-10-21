<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Policy_users extends Model
{
    public $table = 'policy_users';

    // Mass assignable fields
    protected $fillable = ['policy_id', 'user_id', 'read_understood',
        'read_not_understood','read_not_sure', 'status', 'date_read'
    ];

    public function policy()
    {
        return $this->belongsTo(Policy::class, 'policy_id');
    }
	//
	public function employees()
    {
        return $this->belongsTo(HRPerson::class, 'user_id');
    }
}
