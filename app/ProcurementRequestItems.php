<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProcurementRequestItems extends Model
{
    protected $table = 'procurement_request_items';
	
	protected $fillable = ['procurement_id', 'product_id', 'category_id'
	, 'quantity', 'item_name', 'date_added', 'item_price'];
	
	//relationship procurement request items and procurement request (one to many)
    public function proItems() {
         return $this->belongsTo(ProcurementRequest::class, 'procurement_id');
    }
	
	public function products()
    {
        return $this->belongsTo(product_products::class, 'product_id');
    }
	
	public function categories()
    {
        return $this->belongsTo(product_category::class, 'category_id');
    }
}
