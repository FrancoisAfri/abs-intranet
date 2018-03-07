<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DivisionLevelFour extends Model
{
    //Specify the table name
    public $table = 'division_level_fours';

    // Mass assignable fields
    protected $fillable = [
        'name', 'active', 'manager_id'
    ];

    //Relationship Division level 4 and hr_person (manager)
    public function manager() {
        return $this->belongsTo(HRPerson::class, 'manager_id');
    }

    //Relationship Division level 4 and Division level
    public function divisionLevel() {
        return $this->belongsTo(DivisionLevel::class, 'division_level_id');
    }

    //Relationship Division level 4 and Division level 5
    public function parentDiv() {
        return $this->belongsTo(DivisionLevelFive::class, 'parent_id');
    }

    //Relationship Division level 4 and Quote Company Profile
    public function quoteProfile() {
        return $this->hasOne(QuoteCompanyProfile::class, 'division_id');
    }

    //Relationship Division level 4 and Division level 3
    public function childDiv() {
        return $this->hasMany(DivisionLevelThree::class, 'parent_id');
    }

    //Function to a div level 3
    public function addChildDiv($divLvlThree) {
        $divLvlID = DivisionLevel::where('level', 3)->get()->first()->id;
        $divLvlThree->division_level_id = $divLvlID;
        return $this->childDiv()->save($divLvlThree);
    }

    //function ro get lvl 4 divs that belong to a specific lvl 5 div
    public static function divsFromParent($parentID, $incInactive) {
        $divisions = DivisionLevelFour::where('parent_id', $parentID)
            ->where(function ($query) use($incInactive) {
            if ($incInactive == -1) {
                $query->where('active', 1);
            }
        })->get()
            ->sortBy('name')
            ->pluck('id', 'name');
        return $divisions;
    }
}
