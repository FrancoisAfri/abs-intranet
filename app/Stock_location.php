<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock_location extends Model
{
	protected $table = 'stock_locations';

    protected $fillable = ['stock_level_5', 'stock_level_4', 'stock_level_3',
						'stock_level_2', 'stock_level_1', 'product_id'];
			
	 // Product & Location
    public function locationPro()
    {
        return $this->belongsTo(product_products::class, 'product_id')->orderBy('id');
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
