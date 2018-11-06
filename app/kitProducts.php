<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class kitProducts extends Model
{
    protected $table = 'product_kit';
    protected $fillable = ['name', 'date_added', 'status'];

    //  public function Product_Packages() {
    //     return $this->belongsTo(product_products::class, 'products_id');
    // } 
    public function products()
    {
		return $this->belongsTo(product_products::class, 'product_id');
    }

}
