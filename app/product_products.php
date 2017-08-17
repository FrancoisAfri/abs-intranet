<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product_products extends Model
{
    protected $table = 'Product_products';
    protected $fillable = ['name', 'description', 'status', 'status', 'category_id', 'productPrice_id'];

    // Product & category
    public function ProductPackages()
    {
        return $this->belongsTo(product_category::class, 'category_id');
    }

    public function PackadgesTypes()
    {
        return $this->belongsToMany('App\product_packages', 'packages_product_table', 'product_product_id', 'product_packages_id')->withPivot('description');
    }


    public function Product_Promotions()
    {
        return $this->belongsTo(product_promotions::class, 'category_id');
    }

    /**
     * The relationships between product and price.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productPrices()
    {
        return $this->hasMany(product_price::class, 'product_product_id');
    }

    /**
     * The function to add a new price for a product.
     *
     * @param product_price $price
     * @return Model [saved price]
     */
    public function addNewPrice($price)
    {
        return $this->productPrices()->save($price);
    }
}
