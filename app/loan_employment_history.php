<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class loan_employment_history extends Model
{
    //
	protected $table = 'loan_application_employment_history';
	
	// Mass assignable fields
	 protected $fillable = [
        'employer', 'emp_address', 'emp_tel', 'emp_years', 'spouse_emp_years', 'spouse_emp', 'spouse_emp_addr'
		, 'spouse_emp_tel', 'rel_Full', 'rel_relation', 'rel_tel', 'rel_address', 'landlord_name', 'landlord_tel'
		, 'landlord_address', 'asset_period'
    ];
	//Relationship loan_application and Loan history
    public function history() {
        return $this->belongsTo(Loan::class, 'loan_id');
    }
}
