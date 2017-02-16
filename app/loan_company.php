<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class loan_company extends Model
{
    //
	protected $table = 'loan_application_company';
	//
	 protected $fillable = [
        'company_name', 'trading_as', 'vat_number', 'income_tax_number', 'contact_person', 'cell_number', 'work_number'
		, 'home_number', 'fax_number', 'email', 'physical_address', 'postal_address', 'accountant_name', 'account_Address'
		, 'accountant_telephone'
    ];
	//Relationship loan_application and Loan Company
    public function company() {
        return $this->belongsTo(Loan::class, 'loan_id');
    }
}
