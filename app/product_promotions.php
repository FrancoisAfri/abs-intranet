<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product_promotions extends Model
{
    protected $table = 'product_promotions';
    protected $fillable = ['name', 'description', 'start_date', 'end_date', 'discount', 'price', 'status',
        'product_product_id', 'product_packages_id', 'category_id'
    ];

    /**
     * The relationships between promotion and product.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function product()
    {
        return $this->belongsTo(product_products::class, 'product_product_id');
    }

    /**
     * The relationships between promotion and package.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function package()
    {
        return $this->belongsTo(product_packages::class, 'product_packages_id');
    }
}
