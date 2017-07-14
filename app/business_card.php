<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class business_card extends Model
{
    protected $table = 'business_card';
    protected $fillable = ['hr_id' ,'status','email'];

     public function HrPersons() {
        return $this->belongsTo(HRPerson::class, 'hr_id');
    }
}
