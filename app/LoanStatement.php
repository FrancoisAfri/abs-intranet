<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanStatement extends Model
{
    //table name
    protected $table = 'loan_statements';

    // Mass assignable fields
    protected $fillable = [
        'entry_type', 'loan_id', 'beginning_balance', 'ending_balance', 'total_payment', 'interest', 'capital', 'total_due', 'amount', 'rate', 'date_added'
    ];

    //Relationship with loan
    public function loan() {
        return $this->belongsTo(Loan::class, 'user_id');
    }

    protected $dates = [
        //'date_added',
        'created_at',
        'deleted_at'
    ];
}
