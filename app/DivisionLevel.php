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

    //relationship division level details and each specific division level(one to many)
    public function divisionLevelGroup() {
        if ($this->level === 5) {
            return $this->hasMany(DivisionLevelFive::class, 'division_level_id')->orderBy('name');
        }
        elseif ($this->level === 4) {
            return $this->hasMany(DivisionLevelFour::class, 'division_level_id')->orderBy('name');
        }
        if ($this->level === 3) {
            return $this->hasMany(DivisionLevelThree::class, 'division_level_id')->orderBy('name');
        }
        if ($this->level === 2) {
            return $this->hasMany(DivisionLevelTwo::class, 'division_level_id')->orderBy('name');
        }
        if ($this->level === 1) {
            return $this->hasMany(DivisionLevelOne::class, 'division_level_id')->orderBy('name');
        }
    }
    
    //Function to any Division Level regardless it parent/child div 
    public function addDivisionLevelGroup($divLvlGroup) {
        return $this->divisionLevelGroup()->save($divLvlGroup);
    }
}
