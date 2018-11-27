<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestStockItems extends Model
{
    //Specify the table name
    public $table = 'request_stock_items';

    // Mass assignable fields
    protected $fillable = [
        'product_id', 'category_id', 'quantity', 'date_added', 'request_stocks_id'];
		
	    public function stockItems()
    {
        return $this->hasMany(RequestStockItems::class, 'request_stocks_id');
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
