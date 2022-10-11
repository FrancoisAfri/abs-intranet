<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = [
        'name', 'abbreviation'
    ];

    public $table = 'provinces';

    //Province - Country relationship
    public function country() {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
