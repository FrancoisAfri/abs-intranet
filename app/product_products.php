<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product_products extends Model
{
    protected $table = 'Product_products';
      protected $fillable = ['name','description','status','status','category_id','productPrice_id'];

      // Product & category
      public function ProductPackages() {
        return $this->belongsTo(product_category::class, 'category_id');
    } 

     public function PackadgesTypes()
    {
        return $this->belongsToMany('App\product_packages','packages_product_table' ,'product_product_id','product_packages_id')->withPivot('description');
    }


    public function Product_Promotions() {
        return $this->belongsTo(product_promotions::class, 'category_id');
    } 

    #
     public function price_Product() {
        return $this->belongsTo(product_price::class, 'productPrice_id');
    } 
    
}
