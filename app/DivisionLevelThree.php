<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DivisionLevelThree extends Model
{
    //Specify the table name
    public $table = 'division_level_threes';

    // Mass assignable fields
    protected $fillable = [
        'name', 'active', 'manager_id'
    ];

    //Relationship Division level 3 and hr_person (manager)
    public function manager() {
        return $this->belongsTo(HRPerson::class, 'manager_id');
    }

    //Relationship Division level 3 and Division level
    public function divisionLevel() {
        return $this->belongsTo(DivisionLevel::class, 'division_level_id');
    }

    //Relationship Division level 3 and Division level 4
    public function parentDiv() {
        return $this->belongsTo(DivisionLevelFour::class, 'parent_id');
    }

    //Relationship Division level 3 and Division level 2
    public function childDiv() {
        return $this->hasMany(DivisionLevelTwo::class, 'parent_id');
    }

    //Function to a div level 2
    public function addChildDiv($divLvlTwo) {
        return $this->childDiv()->save($divLvlTwo);
    }
}
