<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class stockLevelOne extends Model
{
    //Specify the table name
    public $table = 'stock_level_ones';

    // Mass assignable fields
    protected $fillable = [
        'name', 'active', 'manager_id'
    ];

    //Relationship stock level 1 and hr_person (manager)
    public function storeManager() {
        return $this->belongsTo(HRPerson::class, 'manager_id');
    }

    //Relationship stock level 1 and Division level
    public function stockLevel() {
        return $this->belongsTo(stockLevel::class, 'division_level_id');
    }

   //Relationship stock level 1 and Division level 2
    public function parentStock() {
        return $this->belongsTo(stockLevelTwo::class, 'parent_id');
    }

 //function ro get lvl 1 divs that belong to a specific lvl 2 div
    public static function stockFromParent($parentID, $incInactive) {
        $stocks = stockLevelOne::where('parent_id', $parentID)
            ->where(function ($query) use($incInactive) {
                if ($incInactive == -1) {
                    $query->where('active', 1);
                }
            })->get()
            ->sortBy('name')
            ->pluck('id', 'name');
        return $stocks;
    }
}