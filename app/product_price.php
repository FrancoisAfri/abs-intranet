<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product_price extends Model
{
    //
    protected $table = 'product_price';
    protected $fillable = ['price', 'start_date', 'end_date'];

     public function productPrice() {
        return $this->hasMany(product_products::class, 'productPrice');
    }
      
}
