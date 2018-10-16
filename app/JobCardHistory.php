<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobCardHistory extends Model
{
	protected $table = 'job_card_histories';

    protected $fillable = ['job_card_id', 'status', 'comment', 'action_date', 'user_id'];
	
	public function userName() {
		return $this->belongsTo(HRPerson::class, 'user_id');
    }
}
