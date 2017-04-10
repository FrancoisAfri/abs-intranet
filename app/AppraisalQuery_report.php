<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppraisalQuery_report extends Model
{
    //Specify the table name
    public $table = 'appraisal_query_reports';

    // Mass assignable fields
    protected $fillable = ['kip_id', 'query_code', 'voucher_verification_code'
						, 'query_type', 'hr_id', 'account_no', 'Account_name'
						, 'traveller_name', 'departure_date', 'supplier_name', 'supplier_invoice_number'
						, 'created_by', 'voucher_number', 'invoice_date', 'order_umber'
						, 'invoice_amount', 'date_uploaded', 'comment', 'query_date'];
	
            
    //Relationship kpi and IntegerRange
    public function kpiQueries() {
        return $this->belongsTo(appraisalsKpis::class, 'kpi_id');
    }
}
