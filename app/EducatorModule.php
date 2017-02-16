<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EducatorModule extends Model
{
    //Specify the table name
    public $table = 'educator_modules';

    // Mass assignable fields
    protected $fillable = [
        'name', 'date_registered'
    ];

    //Relationship educator_modules and educator
    public function educator() {
        return $this->belongsTo(educator::class, 'educator_id');
    }
}
