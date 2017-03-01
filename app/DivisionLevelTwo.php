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

    //Function to a div level 1
    public function addChildDiv($divLvlOne) {
        return $this->childDiv()->save($divLvlOne);
    }
}
