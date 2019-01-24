<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product_price extends Model
{
    //
    protected $table = 'product_price';
    protected $fillable = ['price', 'start_date', 'end_date'];

    /**
     * The relationships between product and price.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(product_products::class, 'product_product_id');
    }
}