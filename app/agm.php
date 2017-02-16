<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class agm extends Model
{
    //Specify the table name
    public $table = 'agm';

    // Mass assignable fields
    protected $fillable = [
        'names', 'representative', 'type_attendees', 'email', 'office_number', 'cell_number', 'position', 'contact_type'
    ];
}
