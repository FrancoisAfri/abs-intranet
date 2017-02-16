<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InterestRate extends Model
{
    //table name
    protected $table = 'loan_interest_rate';

    // Mass assignable fields
    protected $fillable = [
        'loan_id', 'prime_rate', 'plus_minus', 'variable_rate', 'loan_interest_rate'
    ];
    
    //Relationship with Loan
    public function loan() {
        return $this->belongsTo(Loan::class, 'loan_id');
    }
}
