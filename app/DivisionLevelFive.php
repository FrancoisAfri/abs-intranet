<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DivisionLevelFive extends Model
{
    //Specify the table name
    public $table = 'division_level_fives';

    // Mass assignable fields
    protected $fillable = [
        'name', 'active', 'manager_id'
    ];

    //Relationship Division level 5 and hr_person (manager)
    public function manager() {
        return $this->belongsTo(HRPerson::class, 'manager_id');
    }

    //Relationship Division level 5 and Division level
    public function divisionLevel() {
        return $this->belongsTo(DivisionLevel::class, 'division_level_id');
    }

    //Relationship Division level 5 and Division level 4
    public function childDiv() {
        return $this->hasMany(DivisionLevelFour::class, 'parent_id');
    }

    //Function to a div level 4
    public function addChildDiv($divLvlFour) {
        $divLvlID = DivisionLevel::where('level', 4)->get()->first()->id;
        $divLvlFour->division_level_id = $divLvlID;
        return $this->childDiv()->save($divLvlFour);
    }
}