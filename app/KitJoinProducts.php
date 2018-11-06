<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KitJoinProducts extends Model
{
    protected $table = 'kin_join_products';
    protected $fillable = ['product_id', 'kit_id', 'category_id', 'status', 'amount_required'];

    //  public function Product_Packages() {
    //     return $this->belongsTo(product_products::class, 'products_id');
    // } 
    /*public function products()
    {
		return $this->belongsTo(product_products::class, 'product_id');
    }*/
}
