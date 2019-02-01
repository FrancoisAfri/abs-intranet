<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestStock extends Model
{
    //Specify the table name
    public $table = 'request_stocks';

    // Mass assignable fields
    protected $fillable = [
        'employee_id', 'on_behalf_of', 'on_behalf_employee_id', 'date_created'
		, 'date_approved', 'status', 'title_name', 'request_remarks', 'store_id'
		, 'request_number', 'invoice_number', 'delivery_number', 'rejection_reason'
		, 'rejected_by', 'rejection_date', 'request_collected', 'collection_document'
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
	
	public function rejectedPerson()
    {
        return $this->belongsTo(HRPerson::class, 'rejected_by');
    }
	
	public function employeeOnBehalf()
    {
        return $this->belongsTo(HRPerson::class, 'on_behalf_employee_id');
    }
	public function requestStatus()
    {
        return $this->belongsTo(Stock_Approvals_level::class, 'status');
    }
	public function stockDeliver()
    {
        return $this->hasMany(StockDelivery::class, 'request_stocks_id');
    }
}
