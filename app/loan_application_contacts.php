<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class loan_application_contacts extends Model
{
    //
	protected $table = 'loan_application_contacts';
	// Mass assignable fields
	 protected $fillable = [
        'ind_name', 'ind_name2', 'ind_name3', 'marital_status', 'marital_status2', 'marital_status3', 'residential_address'
		, 'residential_address2', 'residential_address3', 'spouse_name', 'spouse_name2', 'spouse_name3', 'how_married', 'how_married2'
		, 'how_married3', 'spouse_id', 'spouse_id2', 'spouse_id3', 'number_dep', 'number_dep2', 'number_dep3', 'ind_id_no', 'ind_id_no2'
		, 'ind_id_no3'
    ];
	
	//Relationship loan_application and Loan contacts
    public function contacts() {
        return $this->belongsTo(Loan::class, 'loan_id');
    }
}