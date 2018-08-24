<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class stockInfo extends Model
{
     //Specify the table name
    public $table = 'stock_infos';

    // Mass assignable fields
    protected $fillable = [
        'picture', 'location', 'description', 'product_id', 'allow_vat', 'mass_net'
		, 'minimum_level', 'maximum_level', 'bar_code', 'unit', 'commodity_code'
    ];

    //relationship stock level details and each specific stock level(one to many)
    public function productInfos() {
         return $this->belongsTo(product_products::class, 'product_id');
        
    }
}
