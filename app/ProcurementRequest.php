<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProcurementRequest extends Model
{
    //Specify the table name
    public $table = 'procurement_requests';

    // Mass assignable fields
    protected $fillable = [
        'employee_id', 'on_behalf_of', 'on_behalf_employee_id', 'date_created'
		, 'title_name', 'status',  'date_approved', 'special_instructions'
		, 'detail_of_expenditure', 'justification_of_expenditure', 'po_number', 'invoice_number'
		, 'delivery_number', 'request_collected', 'item_type', 'collection_document'
		, 'collection_note'];
		
	/**
     * Relationship between RequestStock and RequestStockItems
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasManyTo
     */
    public function stockItems()
    {
        return $this->hasMany(RequestStockItems::class, 'request_stocks_id');
    }
	public function employees()
    {
        return $this->belongsTo(HRPerson::class, 'employee_id');
    }
	public function employeeOnBehalf()
    {
        return $this->belongsTo(HRPerson::class, 'on_behalf_employee_id');
    }
}
