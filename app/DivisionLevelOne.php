<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DivisionLevelOne extends Model
{
    //Specify the table name
    public $table = 'division_level_ones';

    // Mass assignable fields
    protected $fillable = [
        'name', 'active', 'manager_id',
    ];

    //Relationship Division level 1 and hr_person (manager)
    public function manager() {
        return $this->belongsTo(HRPerson::class, 'manager_id');
    }

    //Relationship Division level 1 and Division level
    public function divisionLevel() {
        return $this->belongsTo(DivisionLevel::class, 'division_level_id');
    }

    //Relationship Division level 1 and Division level 2
    public function parentDiv() {
        return $this->belongsTo(DivisionLevelTwo::class, 'parent_id');
    }

    //Relationship Division level 1 and Quote Company Profile
    public function quoteProfile() {
        return $this->hasOne(QuoteCompanyProfile::class, 'division_id');
    }

    //public function childDiv() {
        //return false;
   // }

    //function ro get lvl 1 divs that belong to a specific lvl 2 div
    public static function divsFromParent($parentID, $incInactive) {
        $divisions = DivisionLevelOne::where('parent_id', $parentID)
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
