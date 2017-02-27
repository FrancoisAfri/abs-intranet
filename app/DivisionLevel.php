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

    //Relationship Division level and Division level group
    public function divisionLevelGroup() {
        return $this->hasMany(DivisionLevelGroup::class, 'division_level_id');
    }
}
