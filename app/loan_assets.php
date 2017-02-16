<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class loan_assets extends Model
{
    //
	
	protected $table = 'loan_application_assets';
	// Mass assignable fields
	 protected $fillable = [
        'asset_desc', 'asset_state', 'asset_model', 'asset_maf_date', 'asset_supplier', 'asset_supplier_contact', 'asset_supplier_tel'
		, 'asset_cash_price', 'asset_extras', 'asset_extras1', 'asset_extras2', 'asset_total_extras', 'asset_sub_total', 'asset_deposit'
		, 'asset_finance', 'asset_period'
    ];
	//Relationship loan_application and Loan assets
    public function assets() {
        return $this->belongsTo(Loan::class, 'loan_id');
    }
}
