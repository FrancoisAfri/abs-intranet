<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegistrationArea extends Model
{
    //areas array (hard coded)
    public static $areas = [1 => 'MS Excel', 2 => 'MS Powerpoint', 3 => 'MS Word'];

    //Specify the table name
    public $table = 'registration_areas';

    // Mass assignable fields
    protected $fillable = [
        'area_id', 'result'
    ];

    //Relationship registration area and registration
    public function registration() {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    //area name accessor
    public function getNameAttribute() {
        return RegistrationArea::$areas[$this->subject_id];
    }
}
