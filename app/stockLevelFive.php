<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class stockLevelFive extends Model
{
    //Specify the table name
    public $table = 'stock_level_fives';

    // Mass assignable fields
    protected $fillable = [
        'name', 'active', 'manager_id', 'store_address'
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
        return $this->hasMany(stockLevelFour::class, 'parent_id');
    }

    //Function to a div level 4
    public function addChildStock($stockLvlFour) {
        $divLvlID = stockLevel::where('level', 4)->get()->first()->id;
        $stockLvlFour->division_level_id = $divLvlID;
        return $this->childStock()->save($stockLvlFour);
    }
}
