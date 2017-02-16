<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    //Mass assignable fields
    protected $fillable = [
        'name', 'a2_code', 'a3_code', 'numeric_code', 'dialing_code', 'abbreviation'
    ];

    //Define Country - Province relationship
    public function province() {
        return $this->hasMany(Province::class, 'country_id');
    }

    //Function to save Country's hr Province
    public function addProvince(Province $province) {
        return $this->province()->save($province);
    }
}
