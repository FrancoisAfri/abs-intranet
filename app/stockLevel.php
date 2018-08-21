<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class stockLevel extends Model
{
    //Specify the table name
    public $table = 'stock_levels';

    // Mass assignable fields
    protected $fillable = [
        'name', 'level', 'plural_name', 'active'
    ];

    //relationship stock level details and each specific stock level(one to many)
    public function stockLevelGroup() {
        if ($this->level === 5) {
            return $this->hasMany(stockLevelFive::class, 'division_level_id')->orderBy('name');
        }
        elseif ($this->level === 4) {
            return $this->hasMany(stockLevelFour::class, 'division_level_id')->orderBy('name');
        }
        if ($this->level === 3) {
            return $this->hasMany(stockLevelThree::class, 'division_level_id')->orderBy('name');
        }
        if ($this->level === 2) {
            return $this->hasMany(stockLevelTwo::class, 'division_level_id')->orderBy('name');
        }
        if ($this->level === 1) {
            return $this->hasMany(stockLevelOne::class, 'division_level_id')->orderBy('name');
        }
    }
    
    //Function to any Division Level regardless it parent/child div 
    public function addStockLevelGroup($divLvlGroup) {
        return $this->stockLevelGroup()->save($divLvlGroup);
    }
}
