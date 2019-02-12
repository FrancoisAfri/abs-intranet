<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProcurementNotes extends Model
{
    protected $table = 'procurement_notes';
	
	protected $fillable = ['procurement_id', 'added_by', 'note'
	, 'date_added'];
	
	//relationship procurement note and procurement request (one to many)
    public function proNotes() {
         return $this->belongsTo(ProcurementRequest::class, 'procurement_id');
        
    }
	
}
