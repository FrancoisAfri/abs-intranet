<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProcurementHistory extends Model
{
	protected $table = 'procurement_history';

    protected $fillable = ['action', 'status', 'action_date'
		,'user_id','procurement_id'];
		
	    /**
     * Relationship between procurement history and and status
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function statusHistory()
    {
        return $this->belongsTo(ProcurementApproval_steps::class, 'status');
    }
	public function historyEmployees()
    {
        return $this->belongsTo(HRPerson::class, 'user_id');
    }
}
