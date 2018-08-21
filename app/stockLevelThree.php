<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class stockLevelThree extends Model
{
    //Specify the table name
    public $table = 'stock_level_threes';

    // Mass assignable fields
    protected $fillable = [
        'name', 'active', 'manager_id'
    ];

    //Relationship Division level 5 and hr_person (manager)
    public function stockManager() {
        return $this->belongsTo(HRPerson::class, 'manager_id');
    }

    //Relationship Division level 5 and Division level
    public function stockLevel() {
        return $this->belongsTo(stockLevel::class, 'division_level_id');
    }

    //Relationship Division level 5 and Division level 4
    public function childStock() {
        return $this->hasMany(stockLevelTwo::class, 'parent_id');
    }

    //Function to a div level 4
    public function addChildStock($stockLvlTwo) {
        $stockLvlID = stockLevel::where('level', 2)->get()->first()->id;
        $stockLvlTwo->division_level_id = $stockLvlID;
        return $this->childStock()->save($stockLvlTwo);
    }
	
	//function ro get lvl 2 divs that belong to a specific lvl 3 div
    public static function stockFromParent($parentID, $incInactive) {
        $stocks = stockLevelThree::where('parent_id', $parentID)
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
