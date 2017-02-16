<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegistrationSubject extends Model
{
    //subjects array (hard coded)
    public static $subjects = [1 => 'Math', 2 => 'Science'];

    //Specify the table name
    public $table = 'registration_subjects';

    // Mass assignable fields
    protected $fillable = [
        'subject_id', 'result'
    ];

    //Relationship registration subject and registration
    public function registration() {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    //Subject name accessor
    public function getNameAttribute() {
        return RegistrationSubject::$subjects[$this->subject_id];
    }
}
