<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product_promotions extends Model
{
    //
     protected $table = 'product_promotions';
      protected $fillable = ['name','description','start_date','end_date','discount','price','status','product_product_id','product_packages_id','category_id'];

   public function productPromotions() {
        return $this->hasMany(product_products::class, 'category_id ');
    }

    //relationship between promotions & packages
     public function Promotionspackage() {
        return $this->hasMany(product_packages::class, 'category_id ');
    }
}
