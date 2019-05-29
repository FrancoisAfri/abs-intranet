<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProcurementSetup extends Model
{
   protected $table = 'procurement_setups';
	
	protected $fillable = ['is_role_general', 'email_po_to_supplier', 'email_role'
	, 'amount_required_double', 'more_one_quotation', 'request_on_behalf'];

}
