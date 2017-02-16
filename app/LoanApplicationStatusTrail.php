<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanApplicationStatusTrail extends Model
{
    protected $table = 'loan_application_status_trails';

    //Relationship loan_application and loan application status trail assets
    public function loanApplication() {
        return $this->belongsTo(Loan::class, 'loan_id');
    }

    protected $dates = [
        'created_at',
        'deleted_at'
    ];
}
