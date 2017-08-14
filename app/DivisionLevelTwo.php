<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DivisionLevelTwo extends Model
{
    //Specify the table name
    public $table = 'division_level_twos';

    // Mass assignable fields
    protected $fillable = [
        'name', 'active', 'manager_id'
    ];

    //Relationship Division level 2 and hr_person (manager)
    public function manager() {
        return $this->belongsTo(HRPerson::class, 'manager_id');
    }

    //Relationship Division level 2 and Division level
    public function divisionLevel() {
        return $this->belongsTo(DivisionLevel::class, 'division_level_id');
    }

    //Relationship Division level 2 and Division level 3
    public function parentDiv() {
        return $this->belongsTo(DivisionLevelThree::class, 'parent_id');
    }

    //Relationship Division level 2 and Division level 1
    public function childDiv() {
        return $this->hasMany(DivisionLevelOne::class, 'parent_id');
    }

    //Relationship Division level 2 and Quote Company Profile
    public function quoteProfile() {
        return $this->hasOne(QuoteCompanyProfile::class, 'division_id');
    }

    //Function to a div level 1
    public function addChildDiv($divLvlOne) {
        $divLvlID = DivisionLevel::where('level', 1)->get()->first()->id;
        $divLvlOne->division_level_id = $divLvlID;
        return $this->childDiv()->save($divLvlOne);
    }

    //function ro get lvl 2 divs that belong to a specific lvl 3 div
    public static function divsFromParent($parentID, $incInactive) {
        $divisions = DivisionLevelTwo::where('parent_id', $parentID)
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
