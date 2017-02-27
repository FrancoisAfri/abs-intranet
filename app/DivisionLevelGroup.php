<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DivisionLevelGroup extends Model
{
    //Specify the table name
    public $table = 'division_level_groups';

    // Mass assignable fields
    protected $fillable = [
        'name', 'active', 'manager_id'
    ];

    //Relationship Division level group and hr_person (manager)
    public function manager() {
        return $this->belongsTo(HRPerson::class, 'manager_id');
    }

    //Relationship Division level group and Division level
    public function divisionLevel() {
        return $this->belongsTo(DivisionLevel::class, 'division_level_id');
    }
}
