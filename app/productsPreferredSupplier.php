<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class productsPreferredSupplier extends Model
{
    protected $table = 'products_preferred_suppliers';
    protected $fillable = ['order_no', 'supplier_id', 'status', 'description'
	, 'inventory_code','date_last_processed', 'product_id'];
	
	public function ProductSuppliers()
    {
        return $this->belongsTo(product_products::class, 'product_id')->orderBy('id');
    }

}
