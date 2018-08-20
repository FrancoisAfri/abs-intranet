<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product_category extends Model
{
    protected $table = 'product_Category';
    protected $fillable = ['name', 'description', 'status', 'stock_type'];

    public function productCategory()
    {
        return $this->hasMany(product_products::class, 'category_id')->orderBy('name');
    }

    // add a function to add a document type from the relationship
    public function addProducttype(product_products $producttype)
    {
        return $this->productCategory()->save($producttype);
    }


}
