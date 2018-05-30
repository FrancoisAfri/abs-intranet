<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductService extends Model
{
    //Specify the table name
    public $table = 'product_services';

    // Mass assignable fields
    protected $fillable = [
        'description', 'quantity', 'rate'
    ];

    /**
     * Relationship between Services and Quotations
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quote()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }
}
