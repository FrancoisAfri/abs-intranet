<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DivisionLevel extends Model
{
    //Specify the table name
    public $table = 'division_setup';

    // Mass assignable fields
    protected $fillable = [
        'name', 'plural_name', 'active'
    ];

    //relationship division level name and each division level(one to many)
    public function divisionLevelGroup() {
        return $this->hasMany(DivisionLevelOne::class, 'division_level_id');
    }
    //function to add a div level group





    //Relationship Division level and Division level group
    public function divisionLevelGroup() {
        return $this->hasMany(DivisionLevelGroup::class, 'division_level_id');
    }
    
    //Function to a DivisionLevelGroup
    public function addDivisionLevelGroup(DivisionLevelGroup $divLvlGroup) {
        return $this->divisionLevelGroup()->save($divLvlGroup);
    }
}
