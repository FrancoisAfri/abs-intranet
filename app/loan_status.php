<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class loan_status extends Model
{
    //
	protected $table = 'loan_application_status';
	
	// Mass assignable fields
   protected $fillable = [
        'status'
    ];
	
	//Relationship loan_application and Loan status
    public function status() {
        return $this->belongsTo(Loan::class, 'loan_id');
    }
}
