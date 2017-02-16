<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class loan_income extends Model
{
    //
	protected $table = 'loan_application_income';
		
	// Mass assignable fields
	 protected $fillable = [
        'schedule_inc_exp', 'schedule_as_at', 'com_tel', 'gross_salary', 'deductions', 'salary_tax', 'salary_medical'
		, 'salary_pension', 'salary_uif', 'salary_other', 'salary_tot_ded', 'salary_other_inc', 'total_income', 'com_rent_bond'
		, 'com_hire', 'com_loan_repay', 'com_ins_pre', 'com_life_ins', 'com_tel', 'com_maintenance', 'com_plan_sav', 'com_crd_amt'
		, 'com_donations', 'com_edu', 'com_children', 'com_groceries', 'com_cloh_acc', 'com_doc_den', 'com_domes', 'com_sec_sys'
		, 'com_tran', 'com_enter', 'com_dstv', 'com_other', 'com_total', 'surplus_available', 'debt_obl', 'debt_as_at'
		, 'name_creditors', 'total_cre_amount', 'total_cmt_amount'
    ];
	//Relationship loan_application and Loan income
    public function income() {
        return $this->belongsTo(Loan::class, 'loan_id');
    }
}
