<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProcurementQuotations extends Model
{
    protected $table = 'procurement_quotations';
	
	protected $fillable = ['procurement_id', 'supplier_id', 'contact_id'
	, 'total_cost', 'attachment', 'comment', 'date_added'];
	
	//relationship procurement quote and procurement request (one to many)
    public function proQuotes() {
         return $this->belongsTo(ProcurementRequest::class, 'procurement_id');
        
    }
	
	public function companyQuote()
    {
        return $this->belongsTo(ContactCompany::class, 'supplier_id');
    }
	public function clientQuote()
    {
        return $this->belongsTo(ContactPerson::class, 'contact_id');
    }
}
