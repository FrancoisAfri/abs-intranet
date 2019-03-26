<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PublicHoliday extends Model
{
    //Specify the table name
    public $table = 'public_holidays';

    // Mass assignable fields
    protected $fillable = [
        'day', 'country_id', 'year', 'name'
		];
}
