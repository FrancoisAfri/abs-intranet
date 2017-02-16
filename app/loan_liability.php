<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class loan_liability extends Model
{
    //
	protected $table = 'loan_application_liabilities';
				
	// Mass assignable fields
	 protected $fillable = [
        'liabilities_bond', 'loan_payable', 'outs_bal_mot', 'liabilities_motor', 'fur_outs_amt', 'loan_ins_pol', 'lia_inc_tax'
		, 'bank_overdraft', 'crd_inst_limit', 'other_liabilities', 'total_liability', 'net_asset', 'in_fav_of', 'total_cont_lia'
    ];
	//Relationship loan_application and Loan liabilities
    public function liabilities() {
        return $this->belongsTo(Loan::class, 'loan_id');
    }
}
