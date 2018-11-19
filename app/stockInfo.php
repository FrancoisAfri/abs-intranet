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
	//relationship stock level details and each specific stock level(one to many)
    public function stockLevelFive() {
         return $this->belongsTo(stockLevelFive::class, 'stock_level_5');
        
    }
	//relationship stock level details and each specific stock level(one to many)
    public function stockLevelFour() {
         return $this->belongsTo(stockLevelFour::class, 'stock_level_4');
        
    }
	//relationship stock level details and each specific stock level(one to many)
    public function stockLevelThree() {
         return $this->belongsTo(stockLevelThree::class, 'stock_level_3');
        
    }
	//relationship stock level details and each specific stock level(one to many)
    public function stockLevelTwo() {
         return $this->belongsTo(stockLevelTwo::class, 'stock_level_2');
        
    }
	//relationship stock level details and each specific stock level(one to many)
    public function stockLevelOne() {
         return $this->belongsTo(stockLevelOne::class, 'stock_level_1');
        
    }
}
