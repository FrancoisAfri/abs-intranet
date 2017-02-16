<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrimeRate extends Model
{
    //table name
    protected $table = 'loan_prime_rates';
    
    // Mass assignable fields
    protected $fillable = [
        'prime_rate','date_added','current','description'
    ];
}
