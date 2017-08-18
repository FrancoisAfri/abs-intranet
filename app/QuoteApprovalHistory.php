<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuoteApprovalHistory extends Model
{
    //Specify the table name
    public $table = 'quote_company_profiles';

    // Mass assignable fields
    protected $fillable = [
        'quotation_id', 'status', 'comment', 'approval_date', 'user_id'
    ];
}
