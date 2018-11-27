<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class stockInfo extends Model
{
     //Specify the table name
    public $table = 'stock_infos';

    // Mass assignable fields
    protected $fillable = [
        'picture', 'description', 'product_id', 'allow_vat', 'mass_net'
		, 'minimum_level', 'maximum_level', 'bar_code', 'unit', 'commodity_code'
		, 'stock_level_5', 'stock_level_4', 'stock_level_3', 'stock_level_2', 'stock_level_1'
    ];

    //relationship stock level details and each specific stock level(one to many)
    public function productInfos() {
         return $this->belongsTo(product_products::class, 'product_id');
        
    }
}
