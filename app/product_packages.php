<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product_packages extends Model
{
    protected $table = 'product_packages';
    protected $fillable = ['name', 'description', 'products_id', 'discount', 'status'];

    //  public function Product_Packages() {
    //     return $this->belongsTo(product_products::class, 'products_id');
    // } 
    public function products_type()
    {
        #Many to many Relationship Between product_packages and product_products
        return $this->belongsToMany('App\product_products', 'packages_product_table', 'product_packages_id', 'product_product_id')->withPivot('description');

    }

    /**
     * The relationships between package and promotion.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function promotions()
    {
        return $this->hasMany(product_promotions::class, 'product_packages_id');
    }

    /**
     * Relationship between Packages and Quotations
     *
     * @return
     */
    public function quotations()
    {
        return $this->belongsToMany('App\Quotation');
    }
}
