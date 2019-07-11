<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProcurementRequest extends Model
{
    //Specify the table name
    public $table = 'procurement_requests';

    // Mass assignable fields
    protected $fillable = [
        'employee_id','delivery_type', 'on_behalf_of', 'on_behalf_employee_id', 'date_created'
		, 'title_name', 'add_vat', 'status',  'date_approved', 'special_instructions'
		, 'detail_of_expenditure', 'justification_of_expenditure', 'po_number', 'invoice_number'
		, 'delivery_number', 'request_collected', 'item_type', 'jobcard_id'
		, 'stock_request_id', 'collection_note', 'collection_document'];
		
	/**
     * Relationship between procurement and ProcurementRequestItems
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasManyTo
     */
    public function procurementItems()
    {
        return $this->hasMany(ProcurementRequestItems::class, 'procurement_id');
    }
	public function employees()
    {
        return $this->belongsTo(HRPerson::class, 'employee_id');
    }
	public function employeeOnBehalf()
    {
        return $this->belongsTo(HRPerson::class, 'on_behalf_employee_id');
    }
	
	public function requestStatus()
    {
        return $this->belongsTo(ProcurementApproval_steps::class, 'status');
    }
	public function histories()
    {
        return $this->hasMany(ProcurementHistory::class, 'procurement_id');
    }
	public function quotations()
    {
        return $this->hasMany(ProcurementQuotations::class, 'procurement_id');
    }
}