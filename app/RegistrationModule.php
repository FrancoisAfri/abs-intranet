<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegistrationModule extends Model
{
    //Specify the table name
    public $table = 'registration_modules';

    // Mass assignable fields
    protected $fillable = [
        'module_id', 'module_name', 'module_fee', 'result'
    ];

    //Relationship registration module and registration
    public function registration() {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    //module name accessor
    public function getNameAttribute() {
        return $this->module_name;
    }
}
