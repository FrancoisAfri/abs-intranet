<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class loan_insurance extends Model
{
    //
	protected $table = 'loan_application_insurance';
			
	// Mass assignable fields
	 protected $fillable = [
        'exsiting_policy', 'polocy_no', 'renewal_date', 'annual_premium', 'monthly_premium', 'type_security', 'value_security'
		, 'statement_liability', 'statement_as_at', 'fixed_property_cost', 'member_items', 'motor_vehicle_owned', 'loans_receivable', 'net_capital'
		, 'debtors', 'cash_on_hand', 'cash_at_bank', 'surrender_value', 'personal_effects', 'other_assets', 'total_assets'
    ];
	//Relationship loan_application and Loan insurance
    public function insurance() {
        return $this->belongsTo(Loan::class, 'loan_id');
    }
}
