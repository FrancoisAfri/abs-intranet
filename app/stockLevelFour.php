<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class stockLevelFour extends Model
{
    //Specify the table name
    public $table = 'stock_level_fours';

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
        return $this->hasMany(stockLevelThree::class, 'parent_id');
    }

    //Function to a div level 4
    public function addChildStock($stockLvlThree) {
        $divLvlID = stockLevel::where('level', 3)->get()->first()->id;
        $stockLvlThree->division_level_id = $divLvlID;
        return $this->childStock()->save($stockLvlThree);
    }
	
	//function ro get lvl 2 divs that belong to a specific lvl 3 div
    public static function stockFromParent($parentID, $incInactive) {
        $stocks = stockLevelFour::where('parent_id', $parentID)
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
